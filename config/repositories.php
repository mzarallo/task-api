<?php

declare(strict_types=1);

use App\Repositories\Contracts\RolesRepositoryContract;
use App\Repositories\Contracts\UsersRepositoryContract;
use App\Repositories\RolesRepository;
use App\Repositories\UsersRepository;

return [
    UsersRepositoryContract::class => UsersRepository::class,
    RolesRepositoryContract::class => RolesRepository::class,
];
