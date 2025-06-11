<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkPrice extends Model
{
    protected $fillable = [
        'offer_id', 'min_qty', 'price_per_unit', 'tier',
    ];

    public function bid(){
        return $this->belongsTo(Bid::class);
    }

}
