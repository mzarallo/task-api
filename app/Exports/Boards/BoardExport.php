<?php

declare(strict_types=1);

namespace App\Exports\Boards;

use App\Models\Board;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class BoardExport implements FromCollection, ShouldAutoSize, WithHeadings, WithTitle
{
    public function __construct(public Board|Model $board)
    {
    }

    public function collection(): Collection
    {
        return $this->board->stages?->pluck('tasks')?->flatten();
    }

    public function headings(): array
    {
        return array_keys($this->board->stages?->pluck('tasks')?->flatten()?->first()?->getAttributes() ?? []);
    }

    public function title(): string
    {
        return 'Tasks';
    }
}
