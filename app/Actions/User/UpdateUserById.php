<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Repositories\Contracts\UsersRepositoryContract;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateUserById
{
    use AsAction;

    public function __construct(
        private readonly GetUserById $getUserById,
        private readonly UsersRepositoryContract $repository)
    {
    }

    public function handle(int $userId, array $attributes): Model
    {
        $user = $this->repository->find($userId);

        return $this->updateUser($user, $attributes);
    }

    private function updateUser(Model $user, array $attributes): Model
    {
        return $this->repository->update($user->id, $attributes);
    }
}
