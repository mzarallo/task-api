<?php

declare(strict_types=1);

namespace App\Actions\Stages;

use App\Data\Services\Stages\UpdateStageServiceDto;
use App\Models\Stage;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateStageById
{
    use AsAction;

    private int|Stage $stage;

    public function __construct(private readonly GetStageById $getStageById)
    {
    }

    public function handle(int|Stage $stage, UpdateStageServiceDto $dto): Stage
    {
        if ($stage instanceof Stage) {
            $this->stage = $stage;
        }

        if (is_int($stage)) {
            $this->stage = $this->getStageById->handle($stage);
        }

        return $this->updateStage($dto);
    }

    private function updateStage(UpdateStageServiceDto $dto)
    {
        return tap($this->stage)->update($dto->toArray());
    }
}
