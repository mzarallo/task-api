<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Data\Services\Users\GetAllUsersServiceDto;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\Optional;

class GetAllUsers
{
    use AsAction;

    public function handle(GetAllUsersServiceDto $dto): LengthAwarePaginator|Collection
    {
        $users = User::query()
            ->when(! $dto->sort_fields instanceof Optional, function (Builder $builder) use ($dto) {
                collect($dto->sort_fields)->each(fn ($field) => $builder->orderBy($field));
            });

        return $dto->paginated ? $users->paginate(30) : $users->get();
    }
}
