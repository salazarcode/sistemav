<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class SupervisedUserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Any authenticated user can view their supervised users
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $supervisedUser): bool
    {
        // User can view the supervised user if they are in their supervision hierarchy
        return $user->isSupervisorOf($supervisedUser);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Check if user has the create_user permission
        $hasCreateUserPermission = $user->permissions()->where('name', 'create_user')->exists();
        
        // If user has the permission, check if they've reached the limit
        if ($hasCreateUserPermission) {
            $supervisedCount = User::where('parent_id', $user->id)->count();
            return $supervisedCount < 30;
        }
        
        // If user doesn't have the permission, they can't create users
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $supervisedUser): bool
    {
        // Check if user has the edit_user permission
        $hasEditUserPermission = $user->permissions()->where('name', 'edit_user')->exists();
        
        // User can update their DIRECT supervised users if they have the permission
        // Master users can also update their own profile
        if ($user->hasRole('Master')) {
            return $hasEditUserPermission && ($supervisedUser->parent_id === $user->id || $supervisedUser->id === $user->id);
        }
        
        // Regular users can only update their direct supervised users
        return $hasEditUserPermission && $supervisedUser->parent_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $supervisedUser): bool
    {
        // Check if user has the delete_user permission
        $hasDeleteUserPermission = $user->permissions()->where('name', 'delete_user')->exists();
        
        // User can only delete their DIRECT supervised users if they have the permission
        return $hasDeleteUserPermission && $supervisedUser->parent_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $supervisedUser): bool
    {
        // User can only restore their DIRECT supervised users
        return $supervisedUser->parent_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $supervisedUser): bool
    {
        // User can only force delete their DIRECT supervised users
        return $supervisedUser->parent_id === $user->id;
    }
}
