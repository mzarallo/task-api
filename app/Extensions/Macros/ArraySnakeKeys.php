<?php

declare(strict_types=1);

namespace App\Extensions\Macros;

class ArraySnakeKeys
{
    public function macro(array $items): array
    {
        return collect($items)->mapWithKeys(fn ($attribute, $key) => [str()->snake($key) => $attribute])->toArray();
    }
}
