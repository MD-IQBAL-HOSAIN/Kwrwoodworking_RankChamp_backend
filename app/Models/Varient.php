<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Varient extends Model
{
    use HasFactory;
    protected $guarded = [];

    // protected $casts = [
    //     'images' => 'array',
    // ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Add this relationship to Stock
    public function stock()
    {
        return $this->hasOne(Stock::class, 'variant_id');  // Assuming the stock table has a 'variant_id' column
    }



    //
    public function images()
    {
        return $this->hasMany(VarientImage::class);
    }


    //relactionship to variantimage
    public function variantImages()
    {
        return $this->hasMany(VarientImage::class);
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
