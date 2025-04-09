<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use function PHPUnit\Framework\returnArgument;

class Market extends Model
{
    protected $fillable = ['supplier_id', 'description', 'chemical_id', 'user_id', 'price', 'currency', 'stock'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function inventory() {
        return $this->belongsTo(Inventory::class);
    }

    public function chemical() {
        return $this->belongsTo(Chemical::class);
    }
}
