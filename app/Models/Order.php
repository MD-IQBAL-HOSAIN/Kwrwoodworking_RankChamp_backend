<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];


    
    // An order belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // An order belongs to a payment
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    // An order belongs to a product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
