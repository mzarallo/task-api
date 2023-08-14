<?php

declare(strict_types=1);

use App\Events\UserCreated;
use App\Listeners\UserCreatedNotification;

return [
    UserCreated::class => [
        UserCreatedNotification::class, 'handle',
    ],
];
