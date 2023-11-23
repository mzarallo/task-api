<?php

declare(strict_types=1);

namespace App\Actions\Stages;

use App\Data\Services\Stages\GetAllStagesServiceDto;
use App\Models\Stage;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class OrderStages
{
    use AsAction;

    public function __construct(private GetAllStages $getAllStages, private Collection $stages)
    {
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
            $stage->order = $key + 1;
            $stage->save();

            return $stage;
        });
    }
}
