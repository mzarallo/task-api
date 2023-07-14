<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Repositories\Contracts\UsersRepositoryContract;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteUserById
{
    use AsAction;

    public function __construct(private readonly UsersRepositoryContract $repository)
    {

    }

    public function handle(int $userId): bool
    {
        return $this->repository->delete($userId);
    }
}
