<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Actions\Role\AttachRoleToUser;
use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateUser
{
    use AsAction;

    public function __construct(private AttachRoleToUser $attachRoleToUser)
    {
    }

    public function handle(array $fields): User
    {
        return DB::transaction(fn () => $this->attachRoleToUser->run($this->createUser($fields), $fields['role']));
    }

    private function createUser(array $fields): User
    {
        $fields['password'] = $this->generatePassword();
        $user = User::create($fields);
        $this->dispatchEvent($user, $fields['password']);

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
