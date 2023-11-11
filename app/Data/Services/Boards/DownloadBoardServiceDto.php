<?php

declare(strict_types=1);

namespace App\Data\Services\Boards;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

class DownloadBoardServiceDto extends Data
{
    public function __construct(
        #[Required, Exists('users', 'id')]
        public int $user,
        #[Required, Exists('boards', 'id')]
        public int $board,
        #[Required, StringType, In('xls', 'pdf')]
        public ?string $format
    ) {
    }
}
