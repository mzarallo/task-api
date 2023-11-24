<?php

declare(strict_types=1);

namespace App\Actions\Stages;

use App\Data\Services\Stages\DeleteStageByIdServiceDto;
use App\Models\Stage;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteStageById
{
    use AsAction;

    public function handle(DeleteStageByIdServiceDto $dto): ?bool
    {
        return Stage::query()
            ->findOrFail($dto->stage_id)
            ->delete();
    }
}
