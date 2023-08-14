<?php

declare(strict_types=1);

namespace App\Data\Services\Tasks;

use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\BeforeOrEqual;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class CreateTaskServiceDto extends Data
{
    public function __construct(
        #[Required, StringType]
        public string $title,
        #[Nullable, StringType]
        public string|Optional $description,
        #[Required, Date, BeforeOrEqual('start_date')]
        public string $start_date,
        #[Nullable, Date, AfterOrEqual('end_date')]
        public ?string $end_date,
        #[Nullable, ArrayType]
        public ?array $tags,
        #[Nullable, IntegerType]
        public ?int $order,
        #[Required, IntegerType]
        public int $stage_id,
        #[Nullable, IntegerType]
        public ?int $author_id,
    ) {
    }
}
