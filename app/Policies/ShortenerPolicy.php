<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Shortener;
use App\Models\User;

class ShortenerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Shortener');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Shortener $shortener): bool
    {
        return $user->checkPermissionTo('view Shortener');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Shortener');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Shortener $shortener): bool
    {
        return $user->checkPermissionTo('update Shortener');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Shortener $shortener): bool
    {
        return $user->checkPermissionTo('delete Shortener');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Shortener');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Shortener $shortener): bool
    {
        return $user->checkPermissionTo('restore Shortener');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Shortener');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Shortener $shortener): bool
    {
        return $user->checkPermissionTo('replicate Shortener');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Shortener');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Shortener $shortener): bool
    {
        return $user->checkPermissionTo('force-delete Shortener');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Shortener');
    }
}
