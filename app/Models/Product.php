<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    // protected $casts = [
    //     'price' => 'float',
    //     'discount' => 'integer',
    //     'rating' => 'integer',
    //     'status' => 'string',
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime',
    // ];

    /**
     * Get the stock associated with the product.
     */
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    /**
     * Get all variants of the product.
     */
    public function variants()
    {
        return $this->hasMany(Varient::class);
    }

    public function ratings()
    {
        return $this->hasMany(Ratings::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // In the Product model
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function getImageUrlAttribute($value): string|null
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        // Check if the request is an API request
        if (request()->is('api/*') && !empty($value)) {
            // Return the full URL for API requests
            return url($value);
        }

        // Return only the path for web requests
        return $value;
    }
}
