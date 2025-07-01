<?php

declare(strict_types=1);

namespace App\Data\Services\Users;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Symfony\Contracts\Service\Attribute\Required;

class CreateUserServiceDto extends Data
{
    public function __construct(
        #[Required, StringType, Max(255)]
        public string $name,
        #[Required, StringType, Max(255)]
        public string $last_name,
        #[Required, StringType, Email, Max(255)]
        public string $email,
        #[Sometimes, StringType, Max(255)]
        public string|Optional $profile_img_url,
        #[Required, Exists('roles', 'name')]
        public string $role,
    ) {}
}
