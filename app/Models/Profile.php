<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Profile extends Model
{

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->useLogName('profile')
            ->setDescriptionForEvent(fn(string $eventName) => $this->getDescriptionForEvent($eventName));
    }

    public function getDescriptionForEvent(string $eventName) {
        $user = $this->user->name ? $this->user->name : 'System';

        $base = match ($eventName) {
            'created' => "$user created their profile",
            'updated' => "$user updated their profile",
            'deleted' => "$user deleted their profile",
            default => "$user performed $eventName on their profile",
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
        'status', 'score', 'profile_pic', 'company_name', 'phone_number', 'address', 'city', 'postal',
    ];

    public function profilePic() {
        $imagePath = ($this->profile_pic) ? $this->profile_pic : 'profile/rFnWOcUFK9TSL6QAYsU6tI9jDutlaYXpOQS9zo2C.jpg';

        return '/storage/' . $imagePath;
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
