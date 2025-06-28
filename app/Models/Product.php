<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'product_type_id',
        'description',
        'info',
        'base_price',
        'sale_price',
        'quantity',
        'main_image',
        'thumbnails',
    ];

    protected $casts = [
        'info' => 'array',
        'thumbnails' => 'array',
    ];

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlistUsers()
    {
        return $this->belongsToMany(User::class, 'wishlist');
    }
}
