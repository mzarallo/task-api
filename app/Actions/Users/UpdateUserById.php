<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Data\Services\Users\GetUserByIdServiceDto;
use App\Data\Services\Users\UpdateUserByIdServiceDto;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateUserById
{
    use AsAction;

    public function __construct(private readonly GetUserById $getUserById)
    {
    }

    public function handle(int $userId, UpdateUserByIdServiceDto $dto): Model
    {
        $user = $this->getUserById->handle(GetUserByIdServiceDto::validateAndCreate([
            'user_id' => $userId,
        ]));

        return $this->updateUser($user, $dto->toArray());
    }

    private function updateUser(Model|User $user, array $attributes): Model
    {
        return tap($user)->update($attributes);
    }
}
