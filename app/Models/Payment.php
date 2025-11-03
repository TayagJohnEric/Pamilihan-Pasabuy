<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'order_id',
        'amount_paid',
        'payment_method_used',
        'status',
       'payment_proof_url',
       'customer_reference_code',
       'admin_verification_status',
       'admin_notes',
       'verified_by_user_id',
        'payment_processed_at',
        'refunded_at',
        'refund_details',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_method_used' => 'string',
        'status' => 'string',
        'payment_gateway_response' => 'array',
        'payment_processed_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }
}
