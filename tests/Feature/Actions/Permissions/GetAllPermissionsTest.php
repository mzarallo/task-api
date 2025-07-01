<?php

declare(strict_types=1);

use App\Actions\Permissions\GetAllPermissions;
use Database\Seeders\PermissionSeeder;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;

use function Pest\Laravel\seed;

it('get all permissions', function () {
    seed(PermissionSeeder::class);

    $response = GetAllPermissions::make()->handle();

    expect($response)->toBeInstanceOf(Collection::class)
        ->and($response)->toContainOnlyInstancesOf(Permission::class);
});
