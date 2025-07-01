<?php

declare(strict_types=1);

namespace App\Data\Services\Users;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Symfony\Contracts\Service\Attribute\Required;

class GetAllUsersServiceDto extends Data
{
    public function __construct(
        #[Sometimes, ArrayType]
        public array|Optional $sort_fields,
        #[Required, BooleanType]
        public bool $paginated
    ) {}
}
