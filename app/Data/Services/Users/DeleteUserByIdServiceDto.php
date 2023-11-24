<?php

declare(strict_types=1);

namespace App\Data\Services\Users;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class DeleteUserByIdServiceDto extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public int $user_id
    ) {
    }
}
