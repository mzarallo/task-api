<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Data\Services\Users\DeleteUserByIdServiceDto;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteUserById
{
    use AsAction;

    public function handle(DeleteUserByIdServiceDto $dto): bool
    {
        return boolval(User::query()->where('id', $dto->user_id)->delete());
    }
}
