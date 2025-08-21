<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'supplier_id', 'status',
    ];

    public function items() {
        return $this->hasMany(CartItem::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function supplier() {
        return $this->belongsTo(User::class, 'supplier_id');
    }
}
