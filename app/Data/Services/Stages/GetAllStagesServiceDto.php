<?php

declare(strict_types=1);

namespace App\Data\Services\Stages;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class GetAllStagesServiceDto extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public int $board_id,
        #[Sometimes, ArrayType]
        public array|Optional $relations,
        #[Required, BooleanType]
        public bool $paginated
    ) {}
}
