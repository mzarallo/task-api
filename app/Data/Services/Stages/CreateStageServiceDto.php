<?php

declare(strict_types=1);

namespace App\Data\Services\Stages;

use Illuminate\Support\Optional;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class CreateStageServiceDto extends Data
{
    public function __construct(
        #[Required, Max(20)]
        public string $name,
        #[Required, StringType, Regex('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/')]
        public string $hex_color,
        #[Required, IntegerType]
        public int $order,
        #[Required, IntegerType, Exists('boards', 'id')]
        public int $board_id,
        #[Sometimes, BooleanType]
        public bool|Optional $is_final_stage,
    ) {
    }
}
