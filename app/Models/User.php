<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->useLogName('user')
            ->setDescriptionForEvent(fn(string $eventName) => $this->getDescriptionForEvent($eventName));
    }

    public function getDescriptionForEvent(string $eventName) {
        $user = $this->name ? $this->name : 'System';

        $base = match ($eventName) {
            'created' => "$user created their account",
            'updated' => "$user updated their account",
            'deleted' => "$user deleted their account",
            default => "$user performed $eventName on their account",
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

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'paypal_email',
        'banned_at',
        // 'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // protected static function boot() {
    //     parent::boot();

    //     static::created(function ($user) {
    //         $user->profile()->create([
    //             'status' => 'Active',
    //             // 'score' => '100',
    //         ]);
    //     });
    // }

    public function profile() {
        return $this->hasOne(Profile::class);
    }

    public function inventories() {
        return $this->hasMany(Inventory::class);
    }

    public function chemicals() {
        return $this->hasMany(Chemical::class);
    }

    public function inventory_usages() {
        return $this->hasMany(InventoryUsage::class);
    }

    public function alerts() {
        return $this->hasMany(Alert::class);
    }

    public function markets() {
        return $this->hasMany(Market::class);
    }

    public function isSupplier() {
        return $this->role === 'supplier';
    }

    public function userRequests() {
        return $this->hasMany(UserRequest::class);
    }

    public function carts() {
        return $this->hasMany(Cart::class);
    }

    public function bids() {
        return $this->hasMany(Bid::class);
    }

    public function purchaseOrders(){
        return $this->hasMany(PurchaseOrder::class);
    }

    public function receivedOrders(){
        return $this->hasMany(PurchaseOrder::class, 'supplier_id');
    }



    // public function receivedCarts() {
    //     return $this->hasMany(Cart::class, 'supplier_id');
    // }

}
