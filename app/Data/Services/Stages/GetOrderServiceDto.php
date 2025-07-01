<?php

declare(strict_types=1);

namespace App\Data\Services\Stages;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class GetOrderServiceDto extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public int $board_id,
        #[Required, IntegerType]
        public int $order,
    ) {}
}
