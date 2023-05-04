<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface RepositoryContract
{
    public function all(array $sortFields = []): Collection;

    public function create(array $attributes): Model;

    public function update(int $id, array $attributes): Model;

    public function delete(int $id): bool;

    public function find(int $id): Model;
}
