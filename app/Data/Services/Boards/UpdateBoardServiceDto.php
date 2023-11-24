<?php

declare(strict_types=1);

namespace App\Data\Services\Boards;

use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdateBoardServiceDto extends Data
{
    public function __construct(
        #[Sometimes, StringType]
        public string|Optional $name,
        #[Sometimes, StringType, Regex('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/')]
        public string|Optional $hex_color,
    ) {
    }
}
