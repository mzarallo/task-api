<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Data\Services\Users\GetUserByIdServiceDto;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class GetUserById
{
    use AsAction;

    public function handle(GetUserByIdServiceDto $dto): Model
    {
        return User::query()->findOrFail($dto->user_id);
    }
}
