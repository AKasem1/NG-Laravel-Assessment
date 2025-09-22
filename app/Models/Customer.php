<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $guard = 'customer';

    protected $fillable = [
        'name',
        'email', 
        'password',
        'phone',
        'address',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    
    /**
     * Get orders for registered customers
     * Since orders use customer_name/phone instead of email,
     * we'll match based on name and phone
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_name', 'name')
                    ->where('phone', $this->phone);
    }
    
    /**
     * Alternative: Get orders by matching name and phone
     */
    public function getOrdersAttribute()
    {
        return Order::where('customer_name', $this->name)
                   ->where('phone', $this->phone)
                   ->get();
    }
}
