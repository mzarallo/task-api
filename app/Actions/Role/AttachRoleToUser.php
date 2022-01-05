<?php

declare(strict_types=1);

namespace App\Actions\Role;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class AttachRoleToUser
{
    use AsAction;

    public function handle(User $user, string $role): User
    {
        return $user->assignRole($role);
    }
}
