<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class StagePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user): Response
    {
        return $user->can('list-stages') ? Response::allow() : Response::deny();
    }

    public function view(User $user): Response
    {
        return $user->can('list-stages') ? Response::allow() : Response::deny();
    }

    public function delete(User $user): Response
    {
        return $user->can('delete-stages') ? Response::allow() : Response::deny();
    }

    public function edit(User $user): Response
    {
        return $user->can('edit-stages') ? Response::allow() : Response::deny();
    }

    public function create(User $user): Response
    {
        return $user->can('create-stages') ? Response::allow() : Response::deny();
    }
}
