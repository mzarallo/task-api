<?php

declare(strict_types=1);

namespace App\Repositories\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    public function sortBuilder(Builder $builder, array $sortFields): void
    {
        collect($sortFields)->each(function (array|string $sorter) use ($builder) {
            if (is_string($sorter)) {
                $builder->orderBy($sorter);
            }

            if (is_array($sorter)) {
                count($sorter) > 1 ?
                    $builder->orderBy($sorter[0], $sorter[1]) :
                    $builder->orderBy($sorter[0]);
            }
        });
    }
}
