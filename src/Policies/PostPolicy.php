<?php

namespace Taba\Crm\Policies;

use Illuminate\Contracts\Auth\Authenticatable;
use Taba\Crm\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Authenticatable $user): bool
    {
        return $user->can('view_any_post');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Authenticatable $user, Post $post): bool
    {
        return $user->can('view_post');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Authenticatable $user): bool
    {
        return $user->can('create_post');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Authenticatable $user, Post $post): bool
    {
        return $user->can('update_post');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Authenticatable $user, Post $post): bool
    {
        return $user->can('delete_post');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(Authenticatable $user): bool
    {
        return $user->can('delete_any_post');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(Authenticatable $user, Post $post): bool
    {
        return $user->can('force_delete_post');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(Authenticatable $user): bool
    {
        return $user->can('force_delete_any_post');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(Authenticatable $user, Post $post): bool
    {
        return $user->can('restore_post');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(Authenticatable $user): bool
    {
        return $user->can('restore_any_post');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(Authenticatable $user, Post $post): bool
    {
        return $user->can('replicate_post');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(Authenticatable $user): bool
    {
        return $user->can('reorder_post');
    }
}
