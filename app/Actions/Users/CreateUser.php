<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Actions\Roles\AttachRoleToUser;
use App\Data\Services\Roles\AttachRoleToUserDto;
use App\Data\Services\Users\CreateUserServiceDto;
use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

final class CreateUser
{
    use AsAction;

    public function __construct(private readonly AttachRoleToUser $attachRoleToUser)
    {
    }

    public function handle(CreateUserServiceDto $dto): User
    {
        return DB::transaction(function () use ($dto) {
            return $this->attachRoleToUser->handle(
                $this->createUser($dto->except('role')->toArray())->id,
                AttachRoleToUserDto::validateAndCreate([
                    'role' => $dto->role,
                ])
            );
        });
    }

    private function createUser(array $attributes): Model
    {
        $password = $this->generatePassword();

        $user = User::query()->create([
            ...$attributes,
            'password' => $password,
        ]);

        $this->dispatchEvent($user, $password);

        return $user;
    }

    private function generatePassword(): string
    {
        return Str::random(10);
    }

    private function dispatchEvent($user, $password): void
    {
        UserCreated::dispatch($user, $password);
    }
}
