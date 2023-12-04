<?php

declare(strict_types=1);

namespace App\Actions\Stages;

use App\Data\Services\Stages\GetAllStagesServiceDto;
use App\Data\Services\Stages\UpdateStageByIdServiceDto;
use App\Models\Stage;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class OrderStages
{
    use AsAction;

    public function __construct(
        private readonly GetAllStages $getAllStages,
        private readonly UpdateStageById $updateStage,
        private Collection $stages
    ) {
    }

    public function handle(int $boardId): Collection
    {
        $this->getStages($boardId);

        return $this->orderStages();
    }

    private function getStages(int $boardId): void
    {
        $this->stages = $this->getAllStages->handle(
            GetAllStagesServiceDto::validateAndCreate([
                'board_id' => $boardId,
                'paginated' => false,
            ])
        );
    }

    private function orderStages(): Collection
    {
        return $this->stages->map(function (Stage $stage, $key) {
            return $this->updateStage->handle(
                $stage,
                UpdateStageByIdServiceDto::validateAndCreate(['order' => $key + 1])
            );
        });
    }
}
