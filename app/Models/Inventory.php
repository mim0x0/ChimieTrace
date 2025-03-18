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
        'acq_at', 'SDS_file',
    ];
    // protected $fillable = [
    //     'chemical_name', 'image'
    // ];
    // protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    // protected static function boot() {
    //     parent::boot();

    //     static::created(function ($inventory) {
    //         $inventory->update([
    //             'acq_at' => now("Asia/Kuala_Lumpur"),
    //         ]);
    //     });
    // }

    public function image() {
        $imagePath = ($this->image) ? $this->image : "{{ asset('images/sample-chemical.png') }}";

        return '/storage/' . $imagePath;
    }

    public function structure() {
        $imagePath = ($this->chemical_structure) ? $this->chemical_structure : "{{ asset('images/sample-chemical.png') }}";

        return '/storage/' . $imagePath;
    }

    public function SDS() {
        $filePath = ($this->SDS_file) ? $this->SDS_file : "{{ asset('images/sample-chemical.png') }}";

        return '/storage/' . $filePath;
    }
}
