<?php

declare(strict_types=1);

namespace App\Actions\Stages;

use App\Models\Stage;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateStage
{
    use AsAction;

    public function __construct(private GetOrder $getOrder)
    {
    }

    public function handle(array $attributes): Stage
    {
        $attributes['order'] = $this->getOrder->handle(intval($attributes['board_id']), intval($attributes['order']));

        return Stage::create(array_merge($attributes, ['author_id' => auth()->user()->id]));
    }
}
