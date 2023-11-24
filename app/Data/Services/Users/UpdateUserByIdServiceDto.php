<?php

declare(strict_types=1);

namespace App\Data\Services\Users;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdateUserByIdServiceDto extends Data
{
    public function __construct(
        #[Sometimes, StringType, Max(255)]
        public string|Optional $name,
        #[Sometimes, StringType, Max(255)]
        public string|Optional $last_name,
        #[Sometimes, StringType, Max(255)]
        public string|Optional $profile_img_url,
    ) {
    }
}
