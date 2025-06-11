<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Bid extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->useLogName('market')
            ->setDescriptionForEvent(fn(string $eventName) => $this->getDescriptionForEvent($eventName));
    }
    public function getDescriptionForEvent(string $eventName) {
        $user = auth()->check() ? auth()->user()->name : 'System';

        $base = match ($eventName) {
            'created' => "Offer for {$this->market->chemical->chemical_name} ({$this->market->inventory->serial_number})",
            'updated' => "{$this->market->chemical->chemical_name} ({$this->market->inventory->serial_number} Offered By {$this->user->name})",
            'deleted' => "Offer for {$this->market->chemical->chemical_name} ({$this->market->inventory->serial_number})",
            default => "$user performed $eventName offer on {$this->market->chemical->chemical_name} ({$this->market->inventory->serial_number})",
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
        'price', 'quantity', 'delivery', 'notes', 'user_id', 'status', 'stock',
    ];

    public function market() {
        return $this->belongsTo(Market::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function bulkPrices(){
        return $this->hasMany(BulkPrice::class);
    }

}
