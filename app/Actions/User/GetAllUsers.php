<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAllUsers
{
    use AsAction;

    public function handle(): Collection
    {
        return User::orderBy('last_name')->get();
    }
}
