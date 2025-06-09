<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SerialNumber extends Model
{
    protected $fillable = [
        'serial_number', 'counter',
    ];

    public function inventory() {
        return $this->hasMany(Inventory::class);
    }
}
