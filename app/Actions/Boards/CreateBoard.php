<?php

declare(strict_types=1);

namespace App\Actions\Boards;

use App\Models\Board;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateBoard
{
    use AsAction;

    public function handle(array $attributes): Board
    {
        return auth()->user()->boards()->create($attributes);
    }
}
