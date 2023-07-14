<?php

declare(strict_types=1);

namespace App\Actions\Roles;

use App\Repositories\Contracts\RolesRepositoryContract;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAllRoles
{
    use AsAction;

    public function __construct(private readonly RolesRepositoryContract $repository)
    {

    }

    public function handle(): Collection
    {
        return $this->repository->all(sortFields: ['name']);
    }
}
