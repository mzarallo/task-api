<?php

declare(strict_types=1);

namespace App\Data\Services\Boards;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

class CreateBoardServiceDto extends Data
{
    public function __construct(
        #[Required, StringType]
        public string $name,
        #[Required, StringType, Regex('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/')]
        public string $hex_color,
        #[Required, IntegerType, Exists('users', 'id')]
        public int $author_id,
    ) {}
}
