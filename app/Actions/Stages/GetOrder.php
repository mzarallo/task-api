<?php

declare(strict_types=1);

namespace App\Actions\Stages;

use App\Data\Services\Stages\GetOrderServiceDto;
use App\Data\Services\Stages\UpdateStageByIdServiceDto;
use App\Models\Stage;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetOrder
{
    use AsAction;

    public function __construct(
        private readonly OrderStages $orderStages,
        private Collection $stagesOrdered,
        private UpdateStageById $updateStage,
    ) {}

    public function handle(GetOrderServiceDto $dto): int
    {
        $this->stagesOrdered = $this->orderStages($dto->board_id);

        return $this->adjustStagesAndGetOrder($dto->order);
    }

    private function orderStages(int $boardId): Collection
    {
        return $this->orderStages->handle(boardId: $boardId);
    }

    private function adjustStagesAndGetOrder(int $order): int
    {

        if ($this->stagesOrdered->isEmpty()) {
            return 1;
        }

        if ($order >= $this->stagesOrdered->count()) {
            $lastStage = $this->stagesOrdered->last();

            if ($lastStage->is_final_stage) {
                $this->updateStage->handle($lastStage, UpdateStageByIdServiceDto::validateAndCreate([
                    'order' => $this->stagesOrdered->count() + 1,
                ]));

                return $this->stagesOrdered->count();
            }

            return $this->stagesOrdered->count() + 1;
        }

        $this->stagesOrdered
            ->filter(fn (Stage $stage) => $stage->order >= $order)
            ->each(function (Stage $stage) {
                $this->updateStage->handle($stage, UpdateStageByIdServiceDto::validateAndCreate([
                    'order' => $stage->order + 1,
                ]));
            });

        return $order;
    }
}
