<?php

namespace App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use function Symfony\Component\Clock\now;
use Spatie\Activitylog\Traits\LogsActivity;

class Inventory extends Model
{
    use LogsActivity, HasFactory;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->useLogName('inventory')
            ->setDescriptionForEvent(fn(string $eventName) => $this->getDescriptionForEvent($eventName));
    }

    public function getDescriptionForEvent(string $eventName) {
        $user = auth()->check() ? auth()->user()->name : 'System';

        $base = match ($eventName) {
            'created' => "{$this->chemical->chemical_name} ({$this->serial_number} #{$this->container_number})",
            'updated' => "{$this->chemical->chemical_name} ({$this->serial_number} #{$this->container_number})",
            'deleted' => "{$this->chemical->chemical_name} ({$this->serial_number} #{$this->container_number})",
            default => "$user performed $eventName on chemical container: {$this->chemical->chemical_name} ({$this->serial_number} #{$this->container_number})",
        };

    //     if ($eventName === 'updated') {
    //     $changes = collect($this->getChanges());
    //     $original = $this->getOriginal();

    //     $excluded = ['updated_at', 'created_at'];
    //     $filtered = $changes->except($excluded);

    //     if ($filtered->count() === 1 && $filtered->has('quantity')) {
    //         return '';
    //     }

    //     // if ($filtered->isNotEmpty()) {
    //     //     $fieldsChanged = $filtered->map(function ($new, $key) use ($original) {
    //     //         $old = $original[$key] ?? 'N/A';
    //     //         return "$key: '$old' -> '$new'";
    //     //     })->implode(', ');

    //     //     $base .= " --- \nChanged: $fieldsChanged";
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
        'description', 'notes', 'serial_number', 'brand',
        'location', 'quantity','exp_at', 'acq_at', 'SDS_file', 'add_by',
        'chemical_id', 'status',
        'packaging_type', 'unit',
        'min_quantity', 'container_number',
    ];
    // protected $fillable = [
    //     'chemical_name', 'image'
    // ];
    // protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function chemical() {
        return $this->belongsTo(Chemical::class);
    }

    public function usages() {
        return $this->hasMany(InventoryUsage::class);
    }

    public function alerts() {
        return $this->hasMany(Alert::class);
    }

    public function isDepleted() {
        return $this->quantity <= 0;
    }

    public function markets() {
        return $this->hasMany(Market::class);
    }

    public function serialNumbers() {
        return $this->belongsTo(SerialNumber::class);
    }

    // protected static function booted(){
    //     static::saved(function ($inventory) {
    //         self::exportToJson();
    //     });

    //     static::deleted(function ($inventory) {
    //         self::exportToJson();
    //     });
    // }

    // public static function exportToJson(){
    //     $data = self::with(['user', 'chemical'])
    //                 ->get()
    //                 // ->toArray()
    //                 ->groupBy('serial_number')
    //                 ->map(function ($group, $serial) {
    //                     return [
    //                         // 'pageContent' => [
    //                         //     'id' => $inventory->id,
    //                         //     'description' => $inventory->description,
    //                         //     'location' => $inventory->location,
    //                         //     'packaging_type' => $inventory->packaging_type,
    //                         //     'quantity' => $inventory->quantity,
    //                         //     'unit' => $inventory->unit,
    //                         //     'min_quantity' => $inventory->min_quantity,
    //                         //     'status' => $inventory->status,
    //                         //     'acq_at' => $inventory->acq_at,
    //                         //     'exp_at' => $inventory->exp_at,
    //                         //     'add_by' => $inventory->add_by,
    //                         //     'brand' => $inventory->brand,
    //                         //     'notes' => $inventory->notes,
    //                         //     'serial_number' => $inventory->serial_number,
    //                             // 'user' => $inventory->user,
    //                             // 'chemical' => $inventory->chemical
    //                         // ],
    //                         // 'metadata' => [
    //                         //     'id' => $inventory->id
    //                         // ]
    //                         'chunk_id' => $serial,
    //                         'entries' => $group->toArray(),
    //                     ];
    //                 })
    //                 ->values()->toArray();

    //     Storage::disk('local')->put('exports/inventories.json', json_encode($data, JSON_PRETTY_PRINT));
    // }
}
