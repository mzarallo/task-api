<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Repositories\Contracts\UserRepositoryContract;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAllUsers
{
    use AsAction;

    public function __construct(private readonly UserRepositoryContract $repository)
    {

    }

    public function handle(array $sortFields = []): Collection
    {
        return $this->repository->all(sortFields: $sortFields);
    }
}
