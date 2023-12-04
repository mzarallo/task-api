<?php

declare(strict_types=1);

namespace App\Data\Services\Users;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class GetUserByIdServiceDto extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public int $user_id,
        #[Sometimes, ArrayType]
        public array|Optional $where_clause,
        #[Sometimes, ArrayType]
        public array|Optional $relations,
    ) {
    }
}
