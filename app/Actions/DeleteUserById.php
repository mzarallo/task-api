<?php

namespace App\Actions;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteUserById
{
    use AsAction;

    public function handle(int $userId): bool
    {
        return User::findOrFail($userId)->delete();
    }
}
