<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Actions\Roles\AttachRoleToUser;
use App\Events\UserCreated;
use App\Models\User;
use App\Repositories\UsersRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

final class CreateUser
{
    use AsAction;

    public function __construct(
        private readonly AttachRoleToUser $attachRoleToUser,
        private readonly UsersRepository $repository,
    ) {
    }

    public function handle(array $attributes): User
    {
        return DB::transaction(function () use ($attributes) {
            return $this->attachRoleToUser->handle(
                $this->createUser($attributes)->id,
                $attributes['role']
            );
        });
    }

    private function createUser(array $attributes): Model
    {
        $password = $this->generatePassword();
        $user = $this->repository->create([
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
