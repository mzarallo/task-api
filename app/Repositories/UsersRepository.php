<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UsersRepositoryContract;
use App\Repositories\Traits\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

final class UsersRepository implements UsersRepositoryContract
{
    use Sortable;

    protected Builder $model;

    public function __construct()
    {
        $this->model = User::query();
    }

    /**
     * @param  array  $sortFields Un array de strings o un array de arrays
     */
    public function all(array $sortFields = []): Collection
    {
        return $this->model
            ->when($sortFields, function (Builder $builder) use ($sortFields) {
                $this->sortBuilder($builder, $sortFields);
            })->get();
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function update(int $id, array $attributes): Model
    {
        return tap($this->model->find($id))->update($attributes);
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function find(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function assignRoleToUser(string $role, int $userId): User
    {
        return $this->find($userId)->assignRole($role);
    }
}
