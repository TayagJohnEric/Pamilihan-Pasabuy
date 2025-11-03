<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'profile_image_url',
        'email_verified_at',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
        ];
    }

    // Accessors
    public function getNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    // Relationships
    public function rider()
    {
        return $this->hasOne(Rider::class, 'user_id');
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'user_id');
    }

    public function customerOrders()
    {
        return $this->hasMany(Order::class, 'customer_user_id');
    }

    public function riderOrders()
    {
        return $this->hasMany(Order::class, 'rider_user_id');
    }

    public function savedAddresses()
    {
        return $this->hasMany(SavedAddress::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function riderPayouts()
    {
        return $this->hasMany(RiderPayout::class, 'rider_user_id');
    }

    public function vendorPayouts()
    {
        return $this->hasMany(VendorPayout::class, 'vendor_user_id');
    }

    public function ratingsGiven()
    {
        return $this->hasMany(Rating::class, 'user_id');
    }

    public function ratingsReceived()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function reviewedRiderApplications()
    {
        return $this->hasMany(RiderApplication::class, 'reviewed_by_user_id');
    }

    public function reviewedVendorApplications()
    {
        return $this->hasMany(VendorApplication::class, 'reviewed_by_user_id');
    }

    public function orderStatusUpdates()
    {
        return $this->hasMany(OrderStatusHistory::class, 'updated_by_user_id');
    }

    public function shoppingCartItems()
    {
        return $this->hasMany(ShoppingCartItem::class, 'user_id');
    }

    public function verifiedPayments()
    {
        return $this->hasMany(Payment::class, 'verified_by_user_id');
    }
}
