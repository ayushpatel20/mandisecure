<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail as VerifiesEmails;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, VerifiesEmails;

    protected $fillable = [
        'name',
        'business_name',
        'gst_number',
        'mobile',
        'email',
        'password',
        'role',
        'status',
        'profile_photo',
        'address',
        'language',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin(): bool  { return $this->role === 'admin'; }
    public function isBuyer(): bool  { return $this->role === 'buyer'; }
    public function isSeller(): bool { return $this->role === 'seller'; }
    public function isActive(): bool { return $this->status === 'active'; }
    public function isPending(): bool{ return $this->status === 'pending'; }
    public function isBlocked(): bool{ return $this->status === 'blocked'; }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function cart(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Cart::class, 'buyer_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function dashboardRoute(): string
    {
        return match ($this->role) {
            'admin'  => route('admin.dashboard'),
            'seller' => route('seller.dashboard'),
            default  => route('buyer.dashboard'),
        };
    }
}
