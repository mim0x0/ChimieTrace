<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id', 'market_id', 'quantity',
    ];

    public function cart() {
        return $this->belongsTo(Cart::class);
    }

    public function market() {
        return $this->belongsTo(Market::class);
    }
}
