<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Repositories\Contracts\UsersRepositoryContract;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAllUsers
{
    use AsAction;

    public function __construct(private readonly UsersRepositoryContract $repository)
    {

    }

    public function handle(array $sortFields = []): Collection
    {
        return $this->repository->all(sortFields: $sortFields);
    }
}
