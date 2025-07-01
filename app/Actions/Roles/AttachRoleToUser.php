<?php

declare(strict_types=1);

namespace App\Actions\Roles;

use App\Actions\Users\GetUserById;
use App\Data\Services\Roles\AttachRoleToUserDto;
use App\Data\Services\Users\GetUserByIdServiceDto;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class AttachRoleToUser
{
    use AsAction;

    public function __construct(private readonly GetUserById $getUserById) {}

    public function handle(int $userId, AttachRoleToUserDto $dto): User
    {
        return $this->getUserById->handle(
            GetUserByIdServiceDto::validateAndCreate(['user_id' => $userId])
        )->assignRole($dto->role);
    }
}
