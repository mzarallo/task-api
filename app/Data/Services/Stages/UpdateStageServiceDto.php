<?php

declare(strict_types=1);

namespace App\Data\Services\Stages;

use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdateStageServiceDto extends Data
{
    public function __construct(
        #[Required, StringType]
        public string $name,
        #[Sometimes, StringType, Regex('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/')]
        public string|Optional $hex_color,
        #[Sometimes, IntegerType]
        public int|Optional $order,
        #[Sometimes, BooleanType]
        public bool|Optional $is_final_stage,
        #[Required, Exists('boards', 'id')]
        public int $board_id,
    ) {
    }
}
