<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class PermissionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function before(User $user): ?Response
    {
        if ($user->hasRole('Administrator')) {
            return Response::allow();
        }

        return null;
    }

    public function viewAny(User $user): Response
    {
        return $user->can('list-permissions') ? Response::allow() : Response::deny();
    }
}
