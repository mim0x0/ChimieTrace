<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use function PHPUnit\Framework\returnArgument;

class Market extends Model
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
            'created' => "{$this->chemical->chemical_name} Description: {$this->inventory->description}",
            'updated' => "{$this->chemical->chemical_name} Description: {$this->inventory->description}",
            'deleted' => "{$this->chemical->chemical_name} Description: {$this->inventory->description}",
            default => "$user performed $eventName on chemical product for sale: {$this->chemical->chemical_name} Description: {$this->inventory->description}",
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

    protected $fillable =
    [
        'inventory_id', 'description', 'chemical_id', 'user_id', 'price', 'currency', 'stock',
        'quantity_needed', 'notes', 'deadline', 'unit',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function inventory() {
        return $this->belongsTo(Inventory::class);
    }

    public function chemical() {
        return $this->belongsTo(Chemical::class);
    }

    public function items() {
        return $this->hasMany(CartItem::class);
    }

    public function bids() {
        return $this->hasMany(Bid::class);
    }
}
