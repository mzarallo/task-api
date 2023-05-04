<?php

declare(strict_types=1);

use App\Repositories\Contracts\UserRepositoryContract;
use App\Repositories\UserRepository;

return [
    UserRepositoryContract::class => UserRepository::class,
];
