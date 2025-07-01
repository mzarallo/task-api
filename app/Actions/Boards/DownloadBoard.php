<?php

declare(strict_types=1);

namespace App\Actions\Boards;

use App\Actions\Boards\Exports\CreatePdfFromBoard;
use App\Actions\Boards\Exports\CreateXlsFromBoard;
use App\Actions\Users\GetUserById;
use App\Data\Services\Boards\DownloadBoardServiceDto;
use App\Data\Services\Boards\GetBoardByIdServiceDto;
use App\Data\Services\Users\GetUserByIdServiceDto;
use App\Models\Board;
use App\Models\User;
use App\Notifications\Boards\DownloadedBoard;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class DownloadBoard
{
    use AsAction;

    private Board|Model $board;

    private string $filePath;

    public function __construct(
        private readonly GetUserById $getUserById,
        private readonly GetBoardById $getBoardById,
        private readonly CreateXlsFromBoard $createXlsFromBoard,
        private readonly CreatePdfFromBoard $createPdfFromBoard
    ) {}

    protected function getBoard(int $boardId): void
    {
        $this->board = $this->getBoardById->handle(
            GetBoardByIdServiceDto::validateAndCreate([
                'board_id' => $boardId,
                'relations' => ['stages.tasks'],
            ])
        );
    }

    public function handle(DownloadBoardServiceDto $dto): void
    {
        $this->getBoard($dto->board);
        $this->getFile($dto->format);

        $this->getUser($dto->user)->notify(
            new DownloadedBoard(
                board: $this->board,
                filePath: $this->filePath
            )
        );

    }

    protected function getFile(string $format): void
    {
        $this->filePath = match ($format) {
            'pdf' => $this->asPdf(),
            default => $this->asXls()
        };
    }

    protected function asPdf(): string
    {
        return $this->createPdfFromBoard->handle($this->board);
    }

    protected function asXls(): string
    {
        return $this->createXlsFromBoard->handle($this->board);
    }

    protected function getUser(int $userId): Model|User
    {
        return $this->getUserById->handle(GetUserByIdServiceDto::validateAndCreate([
            'user_id' => $userId,
        ]));
    }
}
