<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Boards\Exports;

use App\Actions\Boards\Exports\CreatePdfFromBoard;
use App\Models\Board;
use App\Models\Stage;
use App\Models\Task;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreatePdfFromBoardTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function it_create_pdf_from_boards(): void
    {
        Storage::fake();
        $board = Board::factory()->has(Stage::factory()->has(Task::factory()))->create();

        $response = CreatePdfFromBoard::make()->handle($board);

        $this->assertIsString($response);
        Storage::assertExists('pdf/'.Str::slug('board '.$board->name).'.pdf');
    }
}
