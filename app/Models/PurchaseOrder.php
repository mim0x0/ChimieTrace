<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'user_id', 'supplier_id', 'total', 'status', 'chemical_name', 'variant', 'supplier_name', 'supplier_phone', 'po_number', 'delivery_date', 'terms',
    ];
    public function items() {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function supplier() {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public static function generatePoNumber(){
        $lastPO = self::latest('id')->first();
        $nextId = $lastPO ? $lastPO->id + 1 : 1;
        $year = now()->format('Y');

        return 'PO-' . $year . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }

}
