<?php

declare(strict_types=1);

namespace App\Actions\Roles;

use App\Models\User;
use App\Repositories\UsersRepository;
use Lorisleiva\Actions\Concerns\AsAction;

class AttachRoleToUser
{
    use AsAction;

    public function __construct(private readonly UsersRepository $repository)
    {

    }

    public function handle(int $userId, string $role): User
    {
        return $this->repository->assignRoleToUser($role, $userId);
    }
}
