<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryUsage extends Model
{
    protected $fillable =
    [
        'inventory_id', 'user_id', 'quantity_used', 'reason',
        'inventory_serial', 'chemical_cas', 'chemical_name',
        'user_name', 'quantity_left', 'container_left',
    ];

    public function inventory() {
        return $this->belongsTo(Inventory::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
