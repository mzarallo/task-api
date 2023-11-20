<?php

declare(strict_types=1);

namespace App\Actions\Boards\Exports;

use App\Models\Board;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class CreatePdfFromBoard
{
    use AsAction;

    public function handle(Board|Model $board): string
    {
        $filePath = $this->createFilePath($board->name);

        Storage::put($filePath, $this->makePdf($board));

        return Storage::path($filePath);
    }

    private function createFilePath(string $boardName): string
    {
        return 'pdf/'.Str::slug('board '.$boardName).'.pdf';
    }

    private function makePdf(Board|Model $board): mixed
    {
        return Pdf::loadView(
            'exportables.boards.boardViewAsPdf',
            ['board' => $board->loadMissing(['stages.tasks.author', 'stages.tasks.stage'])]
        )->setPaper('a4', 'landscape')
            ->download()
            ->getOriginalContent();
    }
}
