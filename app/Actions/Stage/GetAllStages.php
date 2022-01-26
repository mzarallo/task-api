<?php

declare(strict_types=1);

namespace App\Actions\Stage;

use App\Actions\Board\GetBoardById;
use App\Models\Board;
use App\Models\Stage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAllStages
{
    use AsAction;

    public function __construct(private GetBoardById $getBoardById)
    {
    }

    public function handle(int $boardId = null, array $relations = []): LengthAwarePaginator
    {
        if ($boardId) {
            return $this->getBoard($boardId)->stages()->with($relations)->orderBy('order')->paginate(15);
        }

        return Stage::with($relations)->orderby('order')->paginate(15);
    }

    private function getBoard(int $id)
    {
        return $this->getBoardById->run($id);
    }
}
