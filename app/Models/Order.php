<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'customer_user_id',
        'rider_user_id',
        'preferred_rider_id',
        'delivery_address_id',
        'order_date',
        'status',
        'delivery_fee',
        'final_total_amount',
        'payment_method',
        'payment_status',
        'special_instructions',
        'delivery_proof_image',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'status' => 'string',
        'delivery_fee' => 'decimal:2',
        'final_total_amount' => 'decimal:2',
        'payment_method' => 'string',
        'payment_status' => 'string',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_user_id');
    }

    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_user_id');
    }

     public function preferredRider()
    {
        return $this->belongsTo(User::class, 'preferred_rider_id');
    }

    public function deliveryAddress()
    {
        return $this->belongsTo(SavedAddress::class, 'delivery_address_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'order_id');
    }
}
