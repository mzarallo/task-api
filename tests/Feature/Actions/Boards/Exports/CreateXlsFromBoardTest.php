<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Boards\Exports;

use App\Actions\Boards\Exports\CreateXlsFromBoard;
use App\Models\Board;
use App\Models\Stage;
use App\Models\Task;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateXlsFromBoardTest extends TestCase
{
    use WithFaker;

    #[Test]
    public function it_create_xls_from_board(): void
    {
        Storage::fake();
        $board = Board::factory()->has(Stage::factory()->has(Task::factory()))->create();

        $response = CreateXlsFromBoard::make()->handle($board);

        $this->assertIsString($response);
        Storage::assertExists('excel/'.Str::slug('board '.$board->name).'.xlsx');
    }
}
