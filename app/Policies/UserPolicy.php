<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function before(User $user): ?bool
    {
        if ($user->hasRole('Administrator')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): Response
    {
        return $user->can('list-users') ? Response::allow() : Response::deny();
    }

    public function view(User $user): Response
    {
        return $user->can('list-users') ? Response::allow() : Response::deny();
    }

    public function delete(User $user): Response
    {
        return $user->can('delete-users') ? Response::allow() : Response::deny();
    }

    public function edit(User $user): Response
    {
        return $user->can('edit-users') ? Response::allow() : Response::deny();
    }

    public function create(User $user): Response
    {
        return $user->can('create-users') ? Response::allow() : Response::deny();
    }
}
