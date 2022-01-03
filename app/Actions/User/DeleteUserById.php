<?php

declare(strict_types=1);

namespace App\Actions\User;

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
