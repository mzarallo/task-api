<?php

declare(strict_types=1);

namespace App\Data\Services\Tasks;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class GetAllTaskServiceDto extends Data
{
    public function __construct(
        #[IntegerType, Exists('boards', 'id')]
        public int|Optional $board,
        #[IntegerType, Exists('stages', 'id')]
        public int|Optional $stage,
        #[ArrayType]
        public array|Optional $relations,
        #[IntegerType, MapInputName('per_page')]
        public int|Optional $perPage = 15,
        #[BooleanType]
        public bool|Optional $paginated = true,
    ) {}
}
