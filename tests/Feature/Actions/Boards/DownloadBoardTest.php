<?php

declare(strict_types=1);

use App\Actions\Boards\DownloadBoard;
use App\Data\Services\Boards\DownloadBoardServiceDto;
use App\Models\Board;
use App\Models\User;
use App\Notifications\Boards\DownloadedBoard;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Permission;

it('send board by mail in different formats', function (string $format) {
    $user = User::factory()->create();
    $board = Board::factory()->create();
    Permission::create(['name' => 'download-boards', 'category' => 'Boards']);
    $user->givePermissionTo('download-boards');

    Notification::fake();

    DownloadBoard::make()->handle(
        DownloadBoardServiceDto::from([
            'user' => $user->id,
            'board' => $board->id,
            'format' => $format,
        ])
    );

    Notification::assertSentToTimes($user, DownloadedBoard::class);
})->with([
    ['xls'],
    ['pdf'],
]);
