<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Chemical extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->useLogName('chemical')
            ->setDescriptionForEvent(fn(string $eventName) => $this->getDescriptionForEvent($eventName));
    }

    public function getDescriptionForEvent(string $eventName) {
        $user = auth()->check() ? auth()->user()->name : 'System';

        $base = match ($eventName) {
            'created' => "{$this->chemical_name} ({$this->CAS_number})",
            'updated' => "{$this->chemical_name} ({$this->CAS_number})",
            'deleted' => "{$this->chemical_name} ({$this->CAS_number})",
            default => "$user performed $eventName on chemical: {$this->chemical_name} ({$this->CAS_number})",
        };

    //     if ($eventName === 'updated') {
    //     $changes = collect($this->getChanges());
    //     $original = $this->getOriginal();

    //     $excluded = ['updated_at', 'created_at'];
    //     $filtered = $changes->except($excluded);

    //     // if ($filtered->isNotEmpty()) {
    //     //     $fieldsChanged = $filtered->map(function ($new, $key) use ($original) {
    //     //         $old = $original[$key] ?? 'N/A';
    //     //         return "$key: '$old' -> '$new'";
    //     //     })->implode(', ');

    //     //     $base .= " \n--- Changed: $fieldsChanged";
    //     // }
    // }
    return $base;
    }

    public function tapActivity(Activity $activity, string $eventName){
        if (auth()->check()) {
            $activity->properties = $activity->properties->merge([
                'custom' => array_merge(
                    $activity->properties->get('custom', []),
                    ['causer_name' => auth()->user()->name,]
                ),
                // 'causer_name' => auth()->user()->name,
                // 'causer_email' => auth()->user()->email,
            ]);
        }
    }

    protected $fillable = [
        'chemical_name', 'empirical_formula', 'CAS_number', 'molecular_weight', 'ec_number',
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

    public function properties(){
        return $this->hasOne(ChemicalProperty::class);
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
