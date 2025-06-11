<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id', 'bid_id', 'quantity', 'stock', 'price', 'subtotal', 'chemical_name', 'variant', 'tier',
    ];
    public function order() {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function bid() {
        return $this->belongsTo(Bid::class);
    }

}
