<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserById
{
    use AsAction;

    public function handle(int $userId): User
    {
        return User::findOrFail($userId);
    }
}
