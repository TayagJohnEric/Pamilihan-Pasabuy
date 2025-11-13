# Payment Rejection Notification System

## Overview
When a rider rejects a customer's payment proof, the system automatically sends notifications to both the customer and admin users. This ensures customers are immediately informed and can resubmit their payment proof.

## Implementation Details

### Rejection Flow

1. **Rider Rejects Payment** (`RiderPaymentController::reject()`)
   - Rider provides a rejection reason
   - Payment status updated to `rider_verification_status = 'rejected'`
   - Rejection reason stored in `rider_verification_notes`
   - Order status logged in history

2. **Customer Notification Sent**
   - Type: `payment_rejected`
   - Title: "Payment Verification Issue"
   - Message includes:
     - Order ID
     - Rejection reason from rider
     - Instructions to resubmit proof
     - Payment ID for reference

3. **Admin Notification Sent**
   - Type: `payment_dispute`
   - Title: "Payment Dispute - Requires Review"
   - Message includes:
     - Order ID
     - Rider name
     - Customer name
     - Payment amount
     - Rejection reason
     - Request for admin review

### Code Location

**RiderPaymentController.php** (Lines 199-271)
```php
public function reject(Request $request, Payment $payment)
{
    // Validation and authorization checks
    
    DB::beginTransaction();
    
    try {
        // Update payment verification status
        $payment->update([
            'rider_verification_status' => 'rejected',
            'rider_verification_notes' => $validated['rejection_reason'],
            'verified_by_rider_id' => $rider->id,
        ]);
        
        // Log order status change
        $this->logOrderStatusChange(...);
        
        DB::commit();
        
        // Send notifications
        $this->notifyCustomerPaymentRejected($payment, $validated['rejection_reason']);
        $this->notifyAdminPaymentDispute($payment, $validated['rejection_reason']);
        
    } catch (\Exception $e) {
        DB::rollBack();
        // Error handling
    }
}
```

### Notification Methods

#### 1. Customer Notification (Lines 309-324)
```php
private function notifyCustomerPaymentRejected($payment, $reason)
{
    Notification::create([
        'user_id' => $payment->order->customer_user_id,
        'type' => 'payment_rejected',
        'title' => 'Payment Verification Issue',
        'message' => [
            'order_id' => $payment->order->id,
            'message' => 'The rider has not received your payment. Please check your GCash transaction and resubmit proof if needed.',
            'reason' => $reason,
            'payment_id' => $payment->id,
        ],
        'related_entity_type' => Order::class,
        'related_entity_id' => $payment->order->id,
    ]);
}
```

#### 2. Admin Notification (Lines 353-374)
```php
private function notifyAdminPaymentDispute($payment, $reason)
{
    $admins = \App\Models\User::where('role', 'admin')->get();
    
    foreach ($admins as $admin) {
        Notification::create([
            'user_id' => $admin->id,
            'type' => 'payment_dispute',
            'title' => 'Payment Dispute - Requires Review',
            'message' => [
                'order_id' => $payment->order->id,
                'rider_name' => $payment->order->rider->name,
                'customer_name' => $payment->order->customer->name,
                'amount' => $payment->amount_paid,
                'reason' => $reason,
                'message' => 'Rider reports payment not received for Order #' . $payment->order->id . '. Please review and resolve.',
            ],
            'related_entity_type' => Payment::class,
            'related_entity_id' => $payment->id,
        ]);
    }
}
```

## Customer Resubmission Flow

When a customer receives the rejection notification:

1. **View Rejection Details**
   - Customer sees notification in their dashboard
   - Notification includes rejection reason
   - Links to order details page

2. **Resubmit Payment Proof** (`CustomerPaymentController::submitGCashProof()`)
   - Customer navigates to order page
   - Clicks "Resubmit Payment Proof" button
   - Uploads new payment screenshot
   - System detects resubmission: `$isResubmission = $payment->rider_verification_status === 'rejected'`

3. **Reset Verification Status**
   ```php
   $payment->update([
       'payment_proof_url' => $paymentProofPath,
       'customer_reference_code' => $validated['customer_reference_code'],
       'admin_verification_status' => 'pending_review',
       'rider_verification_status' => 'pending', // Reset to pending
       'rider_verified_at' => null, // Clear previous timestamp
       'rider_verification_notes' => null, // Clear rejection notes
   ]);
   ```

4. **Rider Notified Again**
   - Rider receives new verification request
   - Can verify or reject the new proof

## Database Schema

### Notifications Table
```sql
CREATE TABLE notifications (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    type VARCHAR(255) NOT NULL,
    title VARCHAR(255) NULL,
    message JSON NOT NULL,
    related_entity_type VARCHAR(255) NULL,
    related_entity_id BIGINT NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Payments Table (Relevant Fields)
```sql
rider_verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending'
rider_verified_at TIMESTAMP NULL
rider_verification_notes TEXT NULL
verified_by_rider_id BIGINT NULL
```

## Notification Types

| Type | Recipient | Trigger | Purpose |
|------|-----------|---------|---------|
| `payment_rejected` | Customer | Rider rejects proof | Inform customer to resubmit |
| `payment_dispute` | Admin | Rider rejects proof | Alert admin for oversight |
| `payment_verified` | Customer | Rider verifies proof | Confirm payment received |
| `payment_verified_by_rider` | Admin | Rider verifies proof | Record keeping |
| `payment_verification_required` | Rider | Customer submits proof | Request rider verification |

## Testing Checklist

### Functional Testing
- [ ] Rider can reject payment with reason
- [ ] Customer receives rejection notification immediately
- [ ] Admin receives dispute notification immediately
- [ ] Rejection reason is displayed in notification
- [ ] Customer can view rejection details on order page
- [ ] Customer can resubmit payment proof after rejection
- [ ] Resubmission resets verification status to 'pending'
- [ ] Rider receives new verification request after resubmission
- [ ] Previous rejection notes are cleared on resubmission

### Notification Content Testing
- [ ] Customer notification includes order ID
- [ ] Customer notification includes rejection reason
- [ ] Customer notification includes resubmission instructions
- [ ] Admin notification includes rider name
- [ ] Admin notification includes customer name
- [ ] Admin notification includes payment amount
- [ ] Admin notification includes rejection reason

### Edge Cases
- [ ] Multiple rejections and resubmissions work correctly
- [ ] Notification sent even if order is in different status
- [ ] Notification links to correct order/payment
- [ ] Notification persists after page refresh
- [ ] Notification can be marked as read

## Related Files

- **Controller**: `app/Http/Controllers/Rider/RiderPaymentController.php`
- **Controller**: `app/Http/Controllers/Customer/CustomerPaymentController.php`
- **Model**: `app/Models/Notification.php`
- **Model**: `app/Models/Payment.php`
- **Migration**: `database/migrations/2025_06_14_015732_create_notifications_table.php`

## Future Enhancements

1. **Email Notifications**: Send email when payment is rejected
2. **SMS Alerts**: Send SMS for urgent payment issues
3. **Push Notifications**: Real-time browser/mobile push notifications
4. **Notification Preferences**: Allow users to customize notification settings
5. **Dispute Resolution**: Add admin interface to resolve payment disputes
6. **Automatic Escalation**: Auto-escalate to admin if rejected multiple times
7. **Payment History**: Show full payment verification history on order page
