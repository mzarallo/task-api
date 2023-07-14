<?php

declare(strict_types=1);

namespace App\Actions\Stages;

use App\Models\Stage;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetOrder
{
    use AsAction;

    public function __construct(private OrderStages $orderStages, private Collection $stagesOrdered)
    {
    }

    public function handle(int $boardId, int $order): int
    {
        $this->stagesOrdered = $this->orderStages($boardId);

        return $this->adjustStagesAndGetOrder($order);
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
            $lastElement = $this->stagesOrdered->last();

            if ($lastElement->is_final_stage) {
                $lastElement->order = $this->stagesOrdered->count() + 1;
                $lastElement->save();

                return $this->stagesOrdered->count();
            }

            return $this->stagesOrdered->count() + 1;
        }

        $this->stagesOrdered
            ->filter(fn (Stage $stage) => $stage->order >= $order)
            ->each(function (Stage $stage) {
                $stage->order = $stage->order + 1;
                $stage->save();
            });

        return $order;
    }
}
