<?php

namespace App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;
use function Symfony\Component\Clock\now;

class Inventory extends Model
{
    protected $fillable = [
        'chemical_name', 'CAS_number', 'serial_number', 'location',
        'quantity', 'SKU', 'image', 'chemical_structure', 'exp_at',
        'reg_at',
    ];
    // protected $fillable = [
    //     'chemical_name', 'image'
    // ];
    // protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    protected static function boot() {
        parent::boot();

        static::created(function ($inventory) {
            $inventory->update([
                'reg_at' => now("Asia/Kuala_Lumpur"),
            ]);
        });
    }
}
