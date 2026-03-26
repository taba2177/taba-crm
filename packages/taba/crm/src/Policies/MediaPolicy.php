<?php

namespace Taba\Crm\Policies;

use Illuminate\Contracts\Auth\Authenticatable;
use Awcodes\Curator\Models\Media;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Authenticatable $user): bool
    {
        return $user->can('view_any_media');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Authenticatable $user, Media $media): bool
    {
        return $user->can('view_media');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Authenticatable $user): bool
    {
        return $user->can('create_media');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Authenticatable $user, Media $media): bool
    {
        return $user->can('update_media');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Authenticatable $user, Media $media): bool
    {
        return $user->can('delete_media');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(Authenticatable $user): bool
    {
        return $user->can('delete_any_media');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(Authenticatable $user, Media $media): bool
    {
        return $user->can('force_delete_media');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(Authenticatable $user): bool
    {
        return $user->can('force_delete_any_media');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(Authenticatable $user, Media $media): bool
    {
        return $user->can('restore_media');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(Authenticatable $user): bool
    {
        return $user->can('restore_any_media');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(Authenticatable $user, Media $media): bool
    {
        return $user->can('replicate_media');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(Authenticatable $user): bool
    {
        return $user->can('reorder_media');
    }
}
