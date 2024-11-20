<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WebsiteShortenerSetting;

class WebsiteShortenerSettingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any WebsiteShortenerSetting');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WebsiteShortenerSetting $websiteshortenersetting): bool
    {
        return $user->checkPermissionTo('view WebsiteShortenerSetting');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create WebsiteShortenerSetting');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WebsiteShortenerSetting $websiteshortenersetting): bool
    {
        return $user->checkPermissionTo('update WebsiteShortenerSetting');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WebsiteShortenerSetting $websiteshortenersetting): bool
    {
        return $user->checkPermissionTo('delete WebsiteShortenerSetting');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any WebsiteShortenerSetting');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WebsiteShortenerSetting $websiteshortenersetting): bool
    {
        return $user->checkPermissionTo('restore WebsiteShortenerSetting');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any WebsiteShortenerSetting');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, WebsiteShortenerSetting $websiteshortenersetting): bool
    {
        return $user->checkPermissionTo('replicate WebsiteShortenerSetting');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder WebsiteShortenerSetting');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WebsiteShortenerSetting $websiteshortenersetting): bool
    {
        return $user->checkPermissionTo('force-delete WebsiteShortenerSetting');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any WebsiteShortenerSetting');
    }
}
