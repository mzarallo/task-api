<?php

declare(strict_types=1);

use App\Actions\Boards\Exports\CreatePdfFromBoard;
use App\Models\Board;
use App\Models\Stage;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

it('create pdf from boards', function () {
    Storage::fake();
    $board = Board::factory()->has(Stage::factory()->has(Task::factory()))->create();

    $response = CreatePdfFromBoard::make()->handle($board);

    expect($response)->toBeString();
    Storage::assertExists('pdf/'.Str::slug('board '.$board->name).'.pdf');
});
