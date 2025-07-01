<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Data\Services\Users\DeleteUserByIdServiceDto;
use App\Data\Services\Users\GetUserByIdServiceDto;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteUserById
{
    use AsAction;

    public function __construct(private readonly GetUserById $getUserById) {}

    public function handle(DeleteUserByIdServiceDto $dto): bool
    {
        $user = $this->getUserById->handle(
            GetUserByIdServiceDto::validateAndCreate([
                'user_id' => $dto->user_id,
            ])
        );

        return boolval($user->delete());
    }
}
