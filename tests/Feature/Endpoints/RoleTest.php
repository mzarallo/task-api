<?php

declare(strict_types=1);

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\seed;

uses(DatabaseMigrations::class);

test('user can obtain all roles', function () {
    seed(RoleSeeder::class);
    seed(PermissionSeeder::class);

    actingAs(User::factory()->create()->givePermissionTo('list-roles'))
        ->getJson(route('api.roles.all'))
        ->assertOk()
        ->assertJson(
            fn (AssertableJson $json) => $json->has(
                'data',
                3,
                fn (AssertableJson $json) => $json->hasAll('id', 'name', 'guard', 'created_at', 'updated_at')
                    ->whereAllType([
                        'id' => 'integer',
                        'name' => 'string',
                        'guard' => 'string',
                        'created_at' => 'string',
                        'updated_at' => 'string',
                    ])
            )
        );
});

test('user cannot get roles without authorization', function () {
    actingAs(User::factory()->create());

    getJson(route('api.roles.all'))
        ->assertForbidden();
});
