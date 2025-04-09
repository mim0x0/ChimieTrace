<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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

    protected static function boot() {
        parent::boot();

        static::created(function ($user) {
            $user->profile()->create([
                'status' => 'Active',
                'score' => '100',
            ]);
        });
    }

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
}
