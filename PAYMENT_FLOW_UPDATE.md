# Payment Flow Update - Option 2 Implementation

## Overview
This document describes the implementation of **Option 2: Payment After Rider Acceptance**. This solution prevents the issue where a customer pays to a rider's GCash account before the rider accepts the order, only to have the rider reject it later.

## Problem Solved
**Original Issue**: Customer pays rider's GCash → Admin approves → Vendor prepares → Rider gets notified → Rider can reject (customer's money is now stuck with wrong rider)

**New Solution**: Order placed → Vendor prepares → Rider gets notified → Rider accepts → Customer pays to ACCEPTING rider's GCash → No more payment to wrong rider

## Changes Made

### 1. Controller Updates

#### `CustomerPaymentController.php`
- **Modified `paymentConfirmation()`**: Now just shows order confirmation, doesn't collect payment
- **Modified `showGCashInstructions()`**: Can now accept an `$orderId` parameter to show payment page after rider acceptance
- **Modified `submitGCashProof()`**: Can handle both legacy (checkout) and new (post-acceptance) flows
- **Added `confirmOnlinePaymentOrder()`**: New method to place order without immediate payment

#### `RiderOrderController.php`
- **Modified `acceptOrder()`**: Now checks if order requires online payment and notifies customer to complete payment
- Sends different notification type (`payment_required`) when payment is needed

### 2. Route Updates (`web.php`)
```php
// Added new route for confirming online payment orders without payment
Route::post('/payment/confirm-online-order', [CustomerPaymentController::class, 'confirmOnlinePaymentOrder'])
    ->name('payment.confirm-online-order');

// Updated GCash instructions route to accept optional order ID
Route::get('/payment/gcash-instructions/{orderId?}', [CustomerPaymentController::class, 'showGCashInstructions'])
    ->name('payment.gcash-instructions');
```

### 3. View Updates

#### `payment-confirmation.blade.php`
- Changed button text from "Proceed to Payment" to "Confirm Order"
- Updated form action to use new `payment.confirm-online-order` route
- Changed notice to explain payment happens AFTER rider acceptance
- Form now submits POST instead of GET

#### `gcash-payment-instructions.blade.php`
- Added hidden `order_id` field for post-acceptance payments
- Can now handle both checkout flow and post-acceptance flow

#### `customer/orders/show.blade.php`
- Added prominent payment button when:
  - Order status is `assigned` (rider accepted)
  - Payment method is `online_payment`
  - Payment status is still `pending`
- Shows yellow alert box prompting customer to complete payment

## New Order Flow

### For Online Payment Orders:

1. **Customer Checkout**
   - Customer selects items, address, payment method (online payment)
   - Customer reviews order → Clicks "Confirm Order"
   - Order created with status: `processing`, payment_status: `pending`
   
2. **Order Processing**
   - Stock decremented, cart cleared
   - Vendors notified of new order
   - Vendors prepare items and mark as "ready for pickup"

3. **Rider Assignment**
   - When all items ready, order status → `awaiting_rider_assignment`
   - System assigns rider (preferred or random available)
   - Rider receives notification

4. **Rider Acceptance** ⭐ KEY CHANGE
   - Rider reviews order and clicks "Accept"
   - Order status → `assigned`
   - **Customer receives notification: "Payment Required"**
   - Customer sees prominent payment button on order page

5. **Customer Payment** ⭐ NEW STEP
   - Customer clicks payment button
   - Views ACCEPTING rider's GCash details
   - Sends payment to rider
   - Uploads proof
   - Payment status → `pending_payment`

6. **Admin Verification**
   - Admin reviews payment proof
   - Admin approves → Payment status: `paid`
   - Order continues to delivery

7. **Delivery**
   - Rider picks up items
   - Rider delivers to customer
   - Order completed

### For COD Orders:
- No changes, existing flow remains the same

## Benefits

✅ **No payment to wrong rider**: Customer only pays AFTER rider confirms acceptance  
✅ **Rider commitment**: Rider accepts knowing customer will pay to them  
✅ **Clear payment responsibility**: Payment tied to specific accepting rider  
✅ **Better user experience**: Customer sees which rider accepted before paying  
✅ **Prevents refund issues**: No need to handle refunds when rider rejects

## Backwards Compatibility

The implementation maintains backwards compatibility:
- Legacy checkout flow still works (though not recommended)
- Can detect if payment is from checkout or post-acceptance via `order_id` presence
- COD orders completely unchanged

## Testing Checklist

- [ ] Customer can place online payment order successfully
- [ ] Order proceeds through vendor preparation without payment
- [ ] Rider receives assignment notification correctly
- [ ] Rider can accept order
- [ ] Customer receives payment required notification after rider acceptance
- [ ] Customer can see payment button on order details page
- [ ] Payment page shows correct rider's GCash details
- [ ] Customer can upload payment proof successfully
- [ ] Admin can verify and approve payment
- [ ] Order continues to delivery after payment approval
- [ ] Rider can still decline before accepting (triggers reassignment)
- [ ] COD orders still work as before

## Notes

- The system still allows rider to decline BEFORE accepting, which triggers automatic reassignment to another rider
- Once rider accepts, they are committed (customer pays to them)
- Payment verification by admin still required before delivery proceeds
- This prevents the original issue while maintaining rider flexibility to decline unsuitable orders before commitment
