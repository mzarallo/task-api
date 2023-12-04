<?php

declare(strict_types=1);

namespace App\Actions\Stages;

use App\Data\Services\Stages\GetStageByIdServiceDto;
use App\Data\Services\Stages\UpdateStageByIdServiceDto;
use App\Models\Stage;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateStageById
{
    use AsAction;

    private int|Stage $stage;

    public function __construct(private readonly GetStageById $getStageById)
    {
    }

    public function handle(int|Stage $stage, UpdateStageByIdServiceDto $dto): Stage
    {
        if ($stage instanceof Stage) {
            $this->stage = $stage;
        }

        if (is_int($stage)) {
            $this->stage = $this->getStageById->handle(
                GetStageByIdServiceDto::validateAndCreate(['stage_id' => $stage])
            );
        }

        return $this->updateStage($dto);
    }

    private function updateStage(UpdateStageByIdServiceDto $dto)
    {
        return tap($this->stage)->update($dto->toArray());
    }
}
