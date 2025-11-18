<?php

namespace Taba\Crm\Policies;

use Illuminate\Contracts\Auth\Authenticatable;
use Taba\Crm\Models\ContactEntry;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactEntryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Authenticatable $user): bool
    {
        return $user->can('view_any_contact::entry');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Authenticatable $user, ContactEntry $contactEntry): bool
    {
        return $user->can('view_contact::entry');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Authenticatable $user): bool
    {
        return $user->can('create_contact::entry');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Authenticatable $user, ContactEntry $contactEntry): bool
    {
        return $user->can('update_contact::entry');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Authenticatable $user, ContactEntry $contactEntry): bool
    {
        return $user->can('delete_contact::entry');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(Authenticatable $user): bool
    {
        return $user->can('delete_any_contact::entry');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(Authenticatable $user, ContactEntry $contactEntry): bool
    {
        return $user->can('force_delete_contact::entry');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(Authenticatable $user): bool
    {
        return $user->can('force_delete_any_contact::entry');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(Authenticatable $user, ContactEntry $contactEntry): bool
    {
        return $user->can('restore_contact::entry');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(Authenticatable $user): bool
    {
        return $user->can('restore_any_contact::entry');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(Authenticatable $user, ContactEntry $contactEntry): bool
    {
        return $user->can('replicate_contact::entry');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(Authenticatable $user): bool
    {
        return $user->can('reorder_contact::entry');
    }
}
