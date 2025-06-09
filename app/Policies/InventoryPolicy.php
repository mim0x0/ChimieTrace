<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InventoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === config('roles.faculty') || $user->role === config('roles.admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Inventory $inventory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === config('roles.admin') || str_ends_with($user->email, config('roles.lecturer'));
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Inventory $inventory): bool
    {
        // return str_ends_with($user->email, '@admin.com');
        return $user->role === config('roles.admin') || str_ends_with($user->email, config('roles.lecturer'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Inventory $inventory): bool
    {
        // return str_ends_with($user->email, '@admin.com');
        return $user->role === config('roles.admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Inventory $inventory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Inventory $inventory): bool
    {
        return false;
    }

    public function use(User $user): bool
    {
        return $user->role === config('roles.faculty') || $user->role === config('roles.admin');
    }

    public function unseal(User $user): bool
    {
        // return str_ends_with($user->email, '@admin.com');
        return $user->role === config('roles.admin') || str_ends_with($user->email, config('roles.lecturer'));
    }
}
