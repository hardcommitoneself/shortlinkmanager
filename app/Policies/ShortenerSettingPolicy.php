<?php

namespace App\Policies;

use App\Models\ShortenerSetting;
use App\Models\User;

class ShortenerSettingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any ShortenerSetting');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ShortenerSetting $shortenersetting): bool
    {
        return $user->checkPermissionTo('view ShortenerSetting');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create ShortenerSetting');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ShortenerSetting $shortenersetting): bool
    {
        return $user->checkPermissionTo('update ShortenerSetting');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ShortenerSetting $shortenersetting): bool
    {
        return $user->checkPermissionTo('delete ShortenerSetting');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any ShortenerSetting');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ShortenerSetting $shortenersetting): bool
    {
        return $user->checkPermissionTo('restore ShortenerSetting');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any ShortenerSetting');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, ShortenerSetting $shortenersetting): bool
    {
        return $user->checkPermissionTo('replicate ShortenerSetting');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder ShortenerSetting');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ShortenerSetting $shortenersetting): bool
    {
        return $user->checkPermissionTo('force-delete ShortenerSetting');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any ShortenerSetting');
    }
}
