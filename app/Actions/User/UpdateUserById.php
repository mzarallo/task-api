<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateUserById
{
    use AsAction;

    public function __construct(private GetUserById $getUserById)
    {
    }

    public function handle(int $userId, array $fields): User
    {
        $user = $this->getUserById->run($userId);

        return $this->updateUser($user, $fields);
    }

    private function updateUser(User $user, array $fields): User
    {
        return tap($user)->update($fields);
    }
}
