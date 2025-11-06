# Rider Assignment Flow Documentation

## Overview
This document explains the complete rider assignment and notification flow for both **COD** and **Online Payment** orders in the Pamilihan Pasabuy system.

---

## Key Principle
**Rider assignment and notification only happens AFTER vendor marks order items as `ready_for_pickup`.**

This ensures that riders are not notified prematurely and only receive delivery assignments when items are actually prepared and ready for pickup.

---

## Complete Flow

### 1. Customer Checkout Process
**Location:** `CustomerCheckoutController::process()` and `customer/checkout/index.blade.php`

The customer makes three key decisions during checkout:
- **Delivery Address:** Selects saved address
- **Payment Method:** COD or Online Payment
- **Rider Selection:**
  - **"Assign One For Me" (system_assign):** System will automatically assign an available rider
  - **"Choose My Rider" (choose_rider):** Customer selects a specific rider from available list

**Data Stored:**
```php
$orderSummary = [
    'delivery_address' => SavedAddress,
    'selected_rider' => User|null,
    'payment_method' => 'cod'|'online_payment',
    'rider_selection_type' => 'choose_rider'|'system_assign',
    // ... other order data
];
```

---

### 2. Order Creation
**Location:** `CustomerPaymentController::createOrderFromSession()`

#### Updated Logic (Applied in this PR):
```php
Order::create([
    'customer_user_id' => $user->id,
    
    // ✅ CRITICAL: rider_user_id is always NULL during order creation
    // Rider assignment happens later when items are ready_for_pickup
    'rider_user_id' => null,
    
    // ✅ Store customer's preference for later use
    'preferred_rider_id' => $orderSummary['rider_selection_type'] === 'choose_rider' 
        ? $orderSummary['selected_rider']->id 
        : null,
    
    'delivery_address_id' => $orderSummary['delivery_address']->id,
    'status' => $paymentMethod === 'cod' ? 'processing' : 'pending_payment',
    'payment_method' => $paymentMethod,
    // ... other fields
]);
```

#### Key Points:
- **`rider_user_id`**: Always `null` during creation for both COD and online payment
- **`preferred_rider_id`**: Stores customer's specific rider choice (if "Choose My Rider" was selected)
- **Order Status**:
  - COD: `processing` → Order finalized immediately → Vendors notified
  - Online Payment: `pending_payment` → Awaits admin verification

---

### 3. Payment Verification (Online Payment Only)
**Location:** `AdminPaymentVerificationController::approve()`

When admin approves payment:
1. Updates payment status to `completed`
2. Updates order status to `processing`
3. Calls `CustomerOrderFulfillmentController::finalizeOrder()`
4. Notifies vendors to prepare items
5. **Does NOT assign rider yet** ✅

---

### 4. Vendor Prepares Items
**Location:** `VendorOrderController::updateOrderItem()`

Vendor marks individual order items:
- `pending` → `preparing` → `ready_for_pickup`

When an item is marked as `ready_for_pickup`:
```php
if ($request->status === 'ready_for_pickup' && $oldStatus !== 'ready_for_pickup') {
    $this->checkAndProcessOrderReadiness($orderItem->order);
}
```

---

### 5. Rider Assignment Trigger
**Location:** `VendorOrderController::checkAndProcessOrderReadiness()`

**Trigger Condition:** ALL order items have `status = 'ready_for_pickup'`

```php
private function checkAndProcessOrderReadiness(Order $order)
{
    $totalItems = $order->orderItems()->count();
    $readyItems = $order->orderItems()->where('status', 'ready_for_pickup')->count();

    // ✅ Only proceed when ALL items are ready
    if ($totalItems === $readyItems && $totalItems > 0) {
        // Update order status
        $order->update(['status' => 'awaiting_rider_assignment']);
        
        // Log status change
        $this->logOrderStatusChange(
            $order->id,
            'awaiting_rider_assignment',
            'All items ready for pickup - awaiting rider assignment',
            null
        );

        // ✅ RIDER ASSIGNMENT HAPPENS HERE
        if ($order->preferred_rider_id) {
            $this->assignSpecificRider($order);
        } else {
            $this->assignBestAvailableRider($order);
        }
    }
}
```

---

### 6. Rider Assignment Logic

#### Option A: Customer Selected Specific Rider
**Location:** `VendorOrderController::assignSpecificRider()`

```php
private function assignSpecificRider(Order $order)
{
    // Try to assign the preferred rider
    $preferredRider = Rider::with('user')
        ->where('user_id', $order->preferred_rider_id)
        ->where('is_available', true)
        ->where('verification_status', 'verified')
        ->first();

    if ($preferredRider && $preferredRider->user->is_active) {
        // ✅ Preferred rider is available
        $order->update(['rider_user_id' => $order->preferred_rider_id]);
        $this->completeRiderAssignment($order, $preferredRider);
    } else {
        // ❌ Preferred rider not available, fallback to auto-assignment
        Log::info("Preferred rider not available, falling back to auto-assignment");
        $this->assignBestAvailableRider($order);
    }
}
```

#### Option B: System Auto-Assignment
**Location:** `VendorOrderController::assignBestAvailableRider()`

```php
private function assignBestAvailableRider(Order $order)
{
    Log::info("[Rider Assignment] Starting random rider search for order {$order->id}");
    
    // Get all available riders
    $availableRiders = $this->getAvailableRiders();

    if ($availableRiders->isEmpty()) {
        // No riders available - notify customer
        return $this->handleNoAvailableRiders($order);
    }

    // Assign random rider
    return $this->attemptRiderAssignment($order, $availableRiders->first());
}

private function getAvailableRiders()
{
    return Rider::with('user')
        ->where('is_available', true)
        ->where('verification_status', 'verified')
        ->whereHas('user', function($query) {
            $query->where('is_active', true);
        })
        ->inRandomOrder()
        ->get();
}
```

---

### 7. Rider Notification
**Location:** `VendorOrderController::completeRiderAssignment()`

**This is when the rider receives notification:**

```php
private function completeRiderAssignment(Order $order, Rider $rider)
{
    // Assign rider to order
    $order->update(['rider_user_id' => $rider->user_id]);

    // Log the assignment
    $this->logOrderStatusChange(
        $order->id, 
        'awaiting_rider_assignment', 
        "Rider assigned (awaiting acceptance): {$rider->user->first_name} {$rider->user->last_name}", 
        null
    );

    // ✅ Notify customer
    $this->createNotification(
        $order->customer_user_id,
        'rider_assignment_pending',
        'Rider Assignment in Progress',
        [
            'order_id' => $order->id,
            'message' => 'A rider is being assigned to your order. Please wait for confirmation.'
        ],
        Order::class,
        $order->id
    );

    // ✅ Notify rider (THIS IS THE KEY NOTIFICATION)
    $this->createNotification(
        $rider->user_id,
        'delivery_assigned_pending',
        'New Delivery Assignment (Action Required)',
        [
            'order_id' => $order->id,
            'customer_name' => $order->customer->first_name . ' ' . $order->customer->last_name,
            'delivery_fee' => $order->delivery_fee,
            'message' => 'You have been assigned a new delivery. Please accept or decline.'
        ],
        Order::class,
        $order->id
    );
}
```

---

## Complete Flow Diagrams

### COD Order Flow
```
Customer Checkout
   ↓
Select: "Assign One For Me" + COD
   ↓
CustomerPaymentController::createOrderFromSession()
   - rider_user_id = null
   - preferred_rider_id = null
   - status = 'processing'
   ↓
CustomerOrderFulfillmentController::finalizeOrder()
   - Deduct stock
   - Clear cart
   - Notify vendors
   ↓
Vendor marks items as 'ready_for_pickup'
   ↓
VendorOrderController::checkAndProcessOrderReadiness()
   - All items ready? ✅
   - status = 'awaiting_rider_assignment'
   ↓
VendorOrderController::assignBestAvailableRider()
   - Find random available rider
   - Set rider_user_id
   ↓
VendorOrderController::completeRiderAssignment()
   - ✅ Notify customer
   - ✅ Notify rider (FIRST TIME RIDER KNOWS ABOUT ORDER)
```

### Online Payment Order Flow
```
Customer Checkout
   ↓
Select: "Choose My Rider" + Online Payment
   ↓
CustomerPaymentController::createOrderFromSession()
   - rider_user_id = null
   - preferred_rider_id = [selected_rider_id]
   - status = 'pending_payment'
   ↓
Customer uploads payment proof
   ↓
Admin verifies payment
   ↓
AdminPaymentVerificationController::approve()
   - status = 'processing'
   - payment_status = 'paid'
   ↓
CustomerOrderFulfillmentController::finalizeOrder()
   - Deduct stock
   - Clear cart
   - Notify vendors
   ↓
Vendor marks items as 'ready_for_pickup'
   ↓
VendorOrderController::checkAndProcessOrderReadiness()
   - All items ready? ✅
   - status = 'awaiting_rider_assignment'
   ↓
VendorOrderController::assignSpecificRider()
   - Check if preferred rider available
   - If yes: assign preferred rider
   - If no: fallback to auto-assignment
   - Set rider_user_id
   ↓
VendorOrderController::completeRiderAssignment()
   - ✅ Notify customer
   - ✅ Notify rider (FIRST TIME RIDER KNOWS ABOUT ORDER)
```

---

## Key Benefits of This Approach

### ✅ Rider Notification Timing
- Riders only receive notifications when items are actually ready for pickup
- Prevents premature notifications that could confuse riders
- Reduces unnecessary rider app activity

### ✅ Consistent Logic
- Both COD and online payment orders follow the same rider assignment flow
- Reduces code complexity and potential bugs
- Easier to maintain and extend

### ✅ Fallback Mechanism
- If customer's preferred rider is unavailable when items are ready, system automatically assigns another available rider
- No order gets stuck waiting for unavailable rider

### ✅ Order Status Clarity
- Clear progression: `processing` → `awaiting_rider_assignment` → `assigned` → `out_for_delivery`
- Status accurately reflects the current state of the order

---

## Files Modified in This Implementation

### Primary Change
- **`app/Http/Controllers/Customer/CustomerPaymentController.php`**
  - Updated `createOrderFromSession()` method
  - Changed `rider_user_id` from `$orderSummary['selected_rider']->id ?? null` to always `null`
  - Added comments explaining the delayed assignment logic

### Files Already Correctly Implemented (No Changes Needed)
- **`app/Http/Controllers/Vendor/VendorOrderController.php`**
  - `checkAndProcessOrderReadiness()` - Triggers rider assignment when all items ready
  - `assignSpecificRider()` - Handles preferred rider assignment
  - `assignBestAvailableRider()` - Handles auto-assignment
  - `completeRiderAssignment()` - Sends notifications to customer and rider

- **`app/Http/Controllers/Admin/AdminPaymentVerificationController.php`**
  - `approve()` - Correctly finalizes online payment orders without assigning rider

- **`app/Http/Controllers/Customer/CustomerOrderFulfillmentController.php`**
  - `finalizeOrder()` - Handles stock deduction, cart clearing, vendor notifications

---

## Testing Scenarios

### Test Case 1: COD with "Assign One For Me"
1. Customer selects COD + "Assign One For Me"
2. Order created with `rider_user_id = null`, `preferred_rider_id = null`
3. Order status = `processing`
4. Vendor marks all items as `ready_for_pickup`
5. System assigns random available rider
6. ✅ Rider receives notification

### Test Case 2: Online Payment with "Choose My Rider"
1. Customer selects Online Payment + specific rider
2. Order created with `rider_user_id = null`, `preferred_rider_id = [rider_id]`
3. Order status = `pending_payment`
4. Admin approves payment → status = `processing`
5. Vendor marks all items as `ready_for_pickup`
6. System assigns preferred rider (if available)
7. ✅ Rider receives notification

### Test Case 3: Preferred Rider Unavailable
1. Customer selects specific rider during checkout
2. Order created with `preferred_rider_id = [rider_id]`
3. Vendor marks items as ready
4. Preferred rider is offline/unavailable
5. ✅ System falls back to auto-assignment
6. ✅ Different available rider receives notification

### Test Case 4: No Riders Available
1. Order items marked as ready
2. No riders currently available
3. ✅ Customer receives notification: "Looking for available rider"
4. Order remains in `awaiting_rider_assignment` status
5. When rider becomes available, admin/system can manually assign

---

## Future Enhancements (Optional)

### 1. Periodic Retry for Unassigned Orders
- Add scheduled job to check orders in `awaiting_rider_assignment` status
- Automatically retry rider assignment every 5-10 minutes
- Alert admin if order remains unassigned for too long

### 2. Rider Acceptance Timeout
- Add timeout for rider to accept assignment (e.g., 10 minutes)
- Auto-reassign to different rider if timeout expires

### 3. Distance-Based Assignment
- Consider rider proximity to pickup location
- Optimize assignment based on delivery efficiency

### 4. Rider Load Balancing
- Track active deliveries per rider
- Prefer assigning to riders with fewer active deliveries

---

## Conclusion

The rider assignment logic now follows a clean, predictable flow:
1. **Order Creation:** No rider assigned, preference stored
2. **Payment Verification:** (Online Payment only) Order approved, vendors notified
3. **Item Preparation:** Vendor marks items as ready
4. **Rider Assignment:** Triggered when ALL items ready
5. **Notification:** Rider notified at the moment of assignment

This ensures riders are only contacted when there's actual work ready for them, improving operational efficiency and user experience.
