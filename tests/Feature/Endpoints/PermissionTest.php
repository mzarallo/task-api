<?php

declare(strict_types=1);

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Testing\Fluent\AssertableJson;
use Spatie\Permission\Models\Permission;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\seed;

uses(\Illuminate\Foundation\Testing\DatabaseMigrations::class);

test('user can obtain all permissions', function () {
    seed(PermissionSeeder::class);

    actingAs(User::factory()->create()->givePermissionTo('list-permissions'))
        ->getJson(route('api.permissions.all'))
        ->assertOk()
        ->assertJson(
            fn (AssertableJson $json) => $json->has(
                'data',
                Permission::query()->count(),
                fn (AssertableJson $json) => $json->hasAll('id', 'name', 'category', 'guard', 'created_at', 'updated_at')
                    ->whereAllType([
                        'id' => 'integer',
                        'name' => 'string',
                        'category' => 'string',
                        'guard' => 'string',
                        'created_at' => 'string',
                        'updated_at' => 'string',
                    ])
            )
        );
});

test('user cannot get permissions without authorization', function () {
    actingAs(User::factory()->create());

    getJson(route('api.permissions.all'))
        ->assertForbidden();
});
