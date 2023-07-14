<?php

declare(strict_types=1);

namespace App\Actions\Stages;

use App\Models\Stage;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateStageById
{
    use AsAction;

    private int|Stage $stage;

    public function __construct(private GetStageById $getStageById)
    {
    }

    public function handle(int|Stage $stage, array $attributes): Stage
    {
        if ($stage instanceof Stage) {
            $this->stage = $stage;
        }

        if (is_int($stage)) {
            $this->stage = $this->getStageById->handle($stage);
        }

        return $this->updateStage($attributes);
    }

    private function updateStage(array $attributes)
    {
        return tap($this->stage)->update($attributes);
    }
}
