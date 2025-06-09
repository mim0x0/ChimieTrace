<?php

namespace App\Policies;

use App\Models\InventoryUsage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InventoryUsagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // return str_ends_with($user->email, '@admin.com');
        return $user->role === config('roles.admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InventoryUsage $inventoryUsage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InventoryUsage $inventoryUsage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InventoryUsage $inventoryUsage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, InventoryUsage $inventoryUsage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, InventoryUsage $inventoryUsage): bool
    {
        return false;
    }
}
