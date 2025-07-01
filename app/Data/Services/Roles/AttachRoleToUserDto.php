<?php

declare(strict_types=1);

namespace App\Data\Services\Roles;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

class AttachRoleToUserDto extends Data
{
    public function __construct(
        #[Required, StringType, Exists('roles', 'name')]
        public string $role
    ) {}
}
