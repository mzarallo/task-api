<?php

declare(strict_types=1);

use App\Actions\Roles\GetAllRoles;
use App\Data\Services\Roles\GetAllRolesServiceDto;
use Database\Seeders\RoleSeeder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\seed;

it('get all roles as collection', function () {
    seed(RoleSeeder::class);

    $response = GetAllRoles::make()->handle(
        GetAllRolesServiceDto::from([
            'paginated' => false,
        ])
    );

    expect($response)->toBeInstanceOf(Collection::class)
        ->and($response)->toContainOnlyInstancesOf(Role::class);
});

it('get all roles as pagination', function () {
    seed(RoleSeeder::class);

    $response = GetAllRoles::make()->handle(
        GetAllRolesServiceDto::from([
            'paginated' => true,
        ])
    );

    expect($response)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($response->items())->toContainOnlyInstancesOf(Role::class);
});

it('get all roles sorted', function () {
    Role::query()->create(['name' => 'AAA']);
    Role::query()->create(['name' => 'CCC']);
    Role::query()->create(['name' => 'BBB']);

    $response = GetAllRoles::make()->handle(
        GetAllRolesServiceDto::from([
            'sort_fields' => ['name'],
            'paginated' => false,
        ])
    );

    expect($response->offsetGet(0)->id)->toEqual(1)
        ->and($response->offsetGet(1)->id)->toEqual(3)
        ->and($response->offsetGet(2)->id)->toEqual(2);
});
