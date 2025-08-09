<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view events list
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        // Master users can view all events
        if ($user->hasRole('Master')) {
            return true;
        }
        
        // User can view their own events
        if ($event->user_id === $user->id) {
            return true;
        }
        
        // User can view events created by their supervisados (users they supervise)
        $supervisadosIds = $user->getAllSupervisedUsersAttribute()->pluck('id')->toArray();
        if (in_array($event->user_id, $supervisadosIds)) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Check if user has the create_event permission
        return $user->permissions()->where('name', 'create_event')->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        // Master users can update all events
        if ($user->hasRole('Master')) {
            return true;
        }
        
        // User can update their own events
        if ($event->user_id === $user->id) {
            return true;
        }
        
        // User can update events created by their supervisados (users they supervise)
        $supervisadosIds = $user->getAllSupervisedUsersAttribute()->pluck('id')->toArray();
        if (in_array($event->user_id, $supervisadosIds)) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        // Master users can delete all events
        if ($user->hasRole('Master')) {
            return true;
        }
        
        // User can delete their own events
        if ($event->user_id === $user->id) {
            return true;
        }
        
        // User can delete events created by their supervisados (users they supervise)
        $supervisadosIds = $user->getAllSupervisedUsersAttribute()->pluck('id')->toArray();
        if (in_array($event->user_id, $supervisadosIds)) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        return $this->delete($user, $event);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        return $this->delete($user, $event);
    }
}
