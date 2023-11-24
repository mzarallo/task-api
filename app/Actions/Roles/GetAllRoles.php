<?php

declare(strict_types=1);

namespace App\Actions\Roles;

use App\Data\Services\Roles\GetAllRolesServiceDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\Optional;
use Spatie\Permission\Models\Role;

class GetAllRoles
{
    use AsAction;

    public function handle(GetAllRolesServiceDto $dto): LengthAwarePaginator|Collection
    {
        $roles = Role::query()
            ->when(! $dto->sort_fields instanceof Optional, function (Builder $builder) use ($dto) {
                collect($dto->sort_fields)->each(fn ($field) => $builder->orderBy($field));
            });

        return $dto->paginated ? $roles->paginate(30) : $roles->get();
    }
}
