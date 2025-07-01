<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Boards;

use App\Actions\Boards\DownloadBoard;
use App\Data\Services\Boards\DownloadBoardServiceDto;
use App\Models\Board;
use App\Models\User;
use App\Notifications\Boards\DownloadedBoard;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DownloadBoardTest extends TestCase
{
    use WithFaker;

    #[Test]
    #[DataProvider('formats')]
    public function it_send_board_by_mail_in_different_formats(string $format): void
    {
        $user = User::factory()->create();
        $board = Board::factory()->create();
        Notification::fake();

        DownloadBoard::make()->handle(
            DownloadBoardServiceDto::from([
                'user' => $user->id,
                'board' => $board->id,
                'format' => $format,
            ])
        );

        Notification::assertSentToTimes($user, DownloadedBoard::class);
    }

    public static function formats(): array
    {
        return [
            ['xls'],
            ['pdf'],
        ];
    }
}
