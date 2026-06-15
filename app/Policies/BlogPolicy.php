<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BlogPolicy
{

public function before(User $user, $ability)
    {
        if ($user->role === 'master|engineer') {
            return true; // Grant all permissions to master users
        }
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Blog $blog): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'master' || $user->role === 'engineer';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Blog $blog): Response
    {
        return $user->id === $blog->user_id
            ? Response::allow()
            : Response::deny('You do not own this blog post.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Blog $blog): Response
    {
        return $user->id === $blog->user_id
            ? Response::allow()
            : Response::deny('You do not own this blog post.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Blog $blog): Response
    {
        return $user->id === $blog->user_id
            ? Response::allow()
            : Response::deny('You do not own this blog post.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Blog $blog): Response
    {
        return $user->id === $blog->user_id
            ? Response::allow()
            : Response::deny('You do not own this blog post.');
    }
}
