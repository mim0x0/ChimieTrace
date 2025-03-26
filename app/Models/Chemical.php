<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chemical extends Model
{
    protected $fillable = [
        'chemical_name', 'CAS_number', 'serial_number',
        'SKU', 'image', 'chemical_structure', 'SDS_file', 'reg_by',
    ];
    // protected $fillable = [
    //     'chemical_name', 'image'
    // ];
    // protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function inventories() {
        return $this->hasMany(Inventory::class);
    }

    public function markets() {
        return $this->hasMany(Market::class);
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
