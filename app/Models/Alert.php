<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'inventory_id', 'message', 'is_read', 'current_overall_quantity', 'current_containers',
        'user_id,'
    ];

    public function inventory() {
        return $this->belongsTo(Inventory::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
