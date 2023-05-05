<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Repositories\Contracts\UsersRepositoryContract;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserById
{
    use AsAction;

    public function __construct(private readonly UsersRepositoryContract $repository)
    {

    }

    public function handle(int $userId): Model
    {
        return $this->repository->find($userId);
    }
}
