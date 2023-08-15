<?php

declare(strict_types=1);

namespace App\Providers;

use App\Extensions\Macros\ArraySnakeKeys;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class MacroExtensionServiceProvider extends ServiceProvider
{
    protected array $macros = [
        Arr::class => [
            'snakeKeys' => ArraySnakeKeys::class,
        ],
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        foreach ($this->macros as $class => $extensions) {
            foreach ($extensions as $name => $extension) {
                $extensionObject = $this->app->make($extension);

                call_user_func_array($class.'::macro', [
                    $name,
                    [$extensionObject, 'macro'],
                ]);
            }
        }
    }
}
