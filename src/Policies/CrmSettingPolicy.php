<?php

namespace Taba\Crm\Policies;

use Illuminate\Contracts\Auth\Authenticatable;
use Taba\Crm\Models\CrmSetting;
use Illuminate\Auth\Access\HandlesAuthorization;

class CrmSettingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Authenticatable $user): bool
    {
        return $user->can('view_any_crm::setting');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Authenticatable $user, CrmSetting $crmSetting): bool
    {
        return $user->can('view_crm::setting');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Authenticatable $user): bool
    {
        return $user->can('create_crm::setting');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Authenticatable $user, CrmSetting $crmSetting): bool
    {
        return $user->can('update_crm::setting');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Authenticatable $user, CrmSetting $crmSetting): bool
    {
        return $user->can('delete_crm::setting');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(Authenticatable $user): bool
    {
        return $user->can('delete_any_crm::setting');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(Authenticatable $user, CrmSetting $crmSetting): bool
    {
        return $user->can('force_delete_crm::setting');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(Authenticatable $user): bool
    {
        return $user->can('force_delete_any_crm::setting');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(Authenticatable $user, CrmSetting $crmSetting): bool
    {
        return $user->can('restore_crm::setting');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(Authenticatable $user): bool
    {
        return $user->can('restore_any_crm::setting');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(Authenticatable $user, CrmSetting $crmSetting): bool
    {
        return $user->can('replicate_crm::setting');
    }
}
