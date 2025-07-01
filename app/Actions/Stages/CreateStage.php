<?php

declare(strict_types=1);

namespace App\Actions\Stages;

use App\Data\Services\Stages\CreateStageServiceDto;
use App\Data\Services\Stages\GetOrderServiceDto;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateStage
{
    use AsAction;

    public function __construct(private readonly GetOrder $getOrder) {}

    public function handle(CreateStageServiceDto $dto): Stage|Model
    {
        $dto->order = $this->getOrder->handle(
            GetOrderServiceDto::validateAndCreate(['board_id' => $dto->board_id, 'order' => $dto->order])
        );

        return Stage::query()->create($dto->toArray());
    }
}
