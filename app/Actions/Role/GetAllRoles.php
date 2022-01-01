<?php

declare(strict_types=1);

namespace App\Actions\Role;

use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;
use Spatie\Permission\Models\Role;

class GetAllRoles
{
    use AsAction;

    public function handle(): Collection
    {
        return Role::orderBy('name')->get();
    }
}
