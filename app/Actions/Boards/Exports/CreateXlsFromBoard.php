<?php

declare(strict_types=1);

namespace App\Actions\Boards\Exports;

use App\Exports\Boards\BoardExport;
use App\Models\Board;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;
use Maatwebsite\Excel\Facades\Excel;

class CreateXlsFromBoard
{
    use AsAction;

    public function handle(Board|Model $board): string
    {
        $filePath = $this->createFilePath($board->name);

        Excel::store(new BoardExport($board), $filePath);

        return Storage::path($filePath);
    }

    private function createFilePath(string $boardName): string
    {
        return 'excel/'.Str::slug('board '.$boardName).'.xlsx';
    }
}
