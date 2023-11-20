<?php

declare(strict_types=1);

namespace App\Data\Services\Stages;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Symfony\Contracts\Service\Attribute\Required;

class GetStageByIdServiceDto extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public int $stage_id,
        #[Sometimes, ArrayType]
        public array|Optional $where_clause,
        #[Sometimes, ArrayType]
        public array|Optional $relations,
    ) {
    }
}
