<?php

declare(strict_types=1);

namespace App\Data\Services\Tasks;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Data;

class DeleteTaskByIdServiceDto extends Data
{
    public function __construct(
        #[IntegerType, Exists('tasks', 'id')]
        public int $task_id,
    ) {}
}
