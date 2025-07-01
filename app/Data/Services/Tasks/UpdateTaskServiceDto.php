<?php

declare(strict_types=1);

namespace App\Data\Services\Tasks;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\BeforeOrEqual;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdateTaskServiceDto extends Data
{
    public function __construct(
        #[Sometimes, StringType]
        public string|Optional $title,
        #[Sometimes, StringType]
        public string|Optional $description,
        #[Sometimes, Date, BeforeOrEqual('start_date'), MapInputName('start_date')]
        public string|Optional $startDate,
        #[Sometimes, Date, AfterOrEqual('end_date'), MapInputName('end_date')]
        public string|Optional $endDate,
        #[Sometimes, ArrayType]
        public array|Optional $tags,
        #[Sometimes, IntegerType]
        public int|Optional $order,
        #[Sometimes, IntegerType]
        public int|Optional $stageId,
    ) {}
}
