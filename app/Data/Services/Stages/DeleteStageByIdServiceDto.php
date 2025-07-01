<?php

declare(strict_types=1);

namespace App\Data\Services\Stages;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

class DeleteStageByIdServiceDto extends Data
{
    public function __construct(
        #[Required, IntegerType]
        public int $stage_id,
    ) {}
}
