<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Data\Services\Users\GetUserByIdServiceDto;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\LaravelData\Optional;

class GetUserById
{
    use AsAction;

    public function handle(GetUserByIdServiceDto $dto): Model
    {
        return User::query()
            ->when(! $dto->where_clause instanceof Optional, fn (Builder $query) => $query->where($dto->where_clause))
            ->with($dto->relations instanceof Optional ? [] : $dto->relations)
            ->findOrFail($dto->user_id);
    }
}
