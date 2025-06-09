<?php

namespace App\Policies;

use App\Models\Market;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MarketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->role === config('roles.admin')) {
            return true;
        };

        return $user->role === config('roles.supplier');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Market $market): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === config('roles.admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Market $markets): bool
    {
        // return false;
        return $user->id === $markets->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Market $markets): bool
    {
        return $user->id === $markets->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Market $market): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Market $market): bool
    {
        return false;
    }

    public function buy(User $user): bool
    {
        return $user->role === config('roles.admin');
    }
}
