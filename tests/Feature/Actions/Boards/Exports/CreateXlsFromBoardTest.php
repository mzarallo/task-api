<?php

declare(strict_types=1);

use App\Actions\Boards\Exports\CreateXlsFromBoard;
use App\Models\Board;
use App\Models\Stage;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

it('create xls from board', function () {
    Storage::fake();
    $board = Board::factory()->has(Stage::factory()->has(Task::factory()))->create();

    $response = CreateXlsFromBoard::make()->handle($board);

    expect($response)->toBeString();
    Storage::assertExists('excel/'.Str::slug('board '.$board->name).'.xlsx');
});
