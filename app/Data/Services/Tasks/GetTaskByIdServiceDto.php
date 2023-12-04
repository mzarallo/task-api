<?php

declare(strict_types=1);

namespace App\Data\Services\Tasks;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Symfony\Contracts\Service\Attribute\Required;

class GetTaskByIdServiceDto extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public int $task_id,
        #[Sometimes, ArrayType]
        public array|Optional $where_clause,
        #[Sometimes, ArrayType]
        public array|Optional $relations,
    ) {
    }
}
