<?php

declare(strict_types=1);

namespace App\Data\Services\Boards;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Symfony\Contracts\Service\Attribute\Required;

class GetAllBoardsServiceDto extends Data
{
    public function __construct(
        #[Sometimes, ArrayType]
        public array|Optional $relations,
        #[Required, BooleanType]
        public bool $paginated,
    ) {}
}
