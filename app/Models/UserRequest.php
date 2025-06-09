<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserRequest extends Model
{
    // use LogsActivity;

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnly($this->fillable)
    //         ->logOnlyDirty()
    //         ->useLogName('inventory')
    //         ->setDescriptionForEvent(fn(string $eventName) => $this->getDescriptionForEvent($eventName));
    // }

    // public function getDescriptionForEvent(string $eventName) {
    //     $user = auth()->check() ? auth()->user()->name : 'System';

    //     $base = match ($eventName) {
    //         'created' => "Request on item: {$this->chemical->chemical_name} ({$this->chemical->CAS_number}) Description: {$this->description}",
    //         'updated' => "Request on item: {$this->chemical->chemical_name} ({$this->chemical->CAS_number}) Description: {$this->description}",
    //         'deleted' => "Request on item: {$this->chemical->chemical_name} ({$this->chemical->CAS_number}) Description: {$this->description}",
    //         default => "$user performed $eventName request on item: {$this->chemical->chemical_name} ({$this->chemical->CAS_number}) Description: {$this->description}",
    //     };

    //     return $base;
    // }

    // public function tapActivity(Activity $activity, string $eventName){
    //     if (auth()->check()) {
    //         $activity->properties = $activity->properties->merge([
    //             'causer_name' => auth()->user()->name,
    //             // 'causer_email' => auth()->user()->email,
    //         ]);
    //     }
    // }

    protected $fillable = [
        'request', 'chemical_id', 'inventory_id', 'market_id', 'respondent_id', 'receiver_type',
        'type', 'item_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function alert() {
        return $this->hasOne(Alert::class);
    }
}
