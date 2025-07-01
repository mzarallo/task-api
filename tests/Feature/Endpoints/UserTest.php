<?php

declare(strict_types=1);

use App\Mail\CredentialsUserMail;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Spatie\Permission\Models\Role;

use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\seed;

uses(DatabaseMigrations::class);

test('user can get all users paginated', function () {
    seed(PermissionSeeder::class);
    User::factory()->count(2)->create();

    actingAs(User::factory()->create()->givePermissionTo('list-users'))
        ->getJson(route('api.users.all'))
        ->assertOk()
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('links', fn (AssertableJson $json) => $json
                    ->hasAll('first', 'last', 'prev', 'next'))
                ->has('meta', fn (AssertableJson $json) => $json
                    ->hasAll('current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total')
                    ->has('links.0', fn (AssertableJson $json) => $json->hasAll('url', 'label', 'active')))
                ->has(
                    'data',
                    3,
                    fn (AssertableJson $json) => $json
                        ->hasAll('id', 'name', 'last_name', 'abbreviation', 'img_profile', 'email')
                        ->whereAllType([
                            'id' => 'integer',
                            'name' => 'string',
                            'last_name' => 'string',
                            'abbreviation' => 'string',
                            'img_profile' => 'string|null',
                            'email' => 'string',
                        ])
                )
        );
});

test('user cannot get users without authorization', function () {
    actingAs(User::factory()->create())
        ->getJson(route('api.users.all'))
        ->assertForbidden();
});

test('user can get single user by id', function () {
    seed(PermissionSeeder::class);
    $user = User::factory()->create();

    actingAs(User::factory()->create()->givePermissionTo('list-users'))
        ->getJson(route('api.users.getById', $user))
        ->assertOk()
        ->assertJson(
            fn (AssertableJson $json) => $json->has(
                'data',
                fn (AssertableJson $json) => $json->hasAll('id', 'name', 'last_name', 'abbreviation', 'img_profile', 'email')
                    ->whereAllType([
                        'id' => 'integer',
                        'name' => 'string',
                        'last_name' => 'string',
                        'abbreviation' => 'string',
                        'img_profile' => 'string|null',
                        'email' => 'string',
                    ])
                    ->where('id', $user->id)
                    ->where('name', $user->name)
                    ->where('last_name', $user->last_name)
                    ->where('abbreviation', $user->abbreviation)
                    ->where('img_profile', $user->profile_img_url)
                    ->where('email', $user->email)
            )
        );
});

test('user gets 404 error when he wants to get a user that does not exist', function () {
    seed(PermissionSeeder::class);

    actingAs(User::factory()->create()->givePermissionTo('list-users'))
        ->getJson(route('api.users.getById', ['user' => 99]))
        ->assertNotFound();
});

test('user cannot get single user by id without permission', function () {
    $user = User::factory()->create();

    actingAs(User::factory()->create())
        ->getJson(route('api.users.getById', $user))
        ->assertForbidden();
});

test('user can delete user by id', function () {
    seed(PermissionSeeder::class);
    $user = User::factory()->create();

    actingAs(User::factory()->create()->givePermissionTo('delete-users'))
        ->deleteJson(route('api.users.deleteById', $user))
        ->assertNoContent();

    assertDatabaseMissing('users', ['id' => $user->id]);
});

test('user cannot delete user by id without permissions', function () {
    $user = User::factory()->create();

    actingAs(User::factory()->create())
        ->deleteJson(route('api.users.deleteById', $user))
        ->assertForbidden();
});

test('user gets 404 error when he wants to delete a user that does not exist', function () {
    seed(PermissionSeeder::class);

    actingAs(User::factory()->create()->givePermissionTo('delete-users'))
        ->deleteJson(route('api.users.deleteById', ['user' => 99]))
        ->assertStatus(404);
});
test('user can update users', function () {
    seed(RoleSeeder::class);
    seed(PermissionSeeder::class);
    $user = User::factory()->create();
    $name = fake()->name();
    $lastName = fake()->lastName();
    $params = [
        'name' => $name,
        'last_name' => $lastName,
    ];

    actingAs(User::factory()->create()->givePermissionTo('edit-users'))
        ->patchJson(
            route('api.users.updateById', $user),
            [
                'name' => $params['name'],
                'last_name' => $params['last_name'],
            ]
        )->assertOk()->assertJson(
            fn (AssertableJson $json) => $json
                ->where('name', $params['name'])
                ->where('last_name', $params['last_name'])
                ->where('img_profile', $user->profile_img_url)
                ->etc()
        );
});

test('user cannot update users without permissions', function () {
    $user = User::factory()->create();

    actingAs(User::factory()->create())
        ->patchJson(route('api.users.updateById', $user))
        ->assertForbidden();
});

test('user gets 404 error when he wants update a user that does not exist', function () {
    seed(PermissionSeeder::class);

    actingAs(User::factory()->create()->givePermissionTo('edit-users'))
        ->patchJson(route('api.users.updateById', ['user' => 99]))
        ->assertNotFound();
});

test('user can create users', function () {
    Mail::fake();
    seed(RoleSeeder::class);
    seed(PermissionSeeder::class);
    $name = fake()->name();
    $lastName = fake()->lastName();
    $attributes = [
        'name' => $name,
        'last_name' => $lastName,
        'profile_img_url' => fake()->url(),
        'email' => fake()->email(),
        'role' => Role::all()->random()->name,
        'abbreviation' => Str::upper(Str::substr($name, 0, 1).Str::substr($lastName, 0, 1)),
    ];

    actingAs(User::factory()->create()->givePermissionTo('create-users'))
        ->postJson(route('api.users.create', $attributes))
        ->assertCreated()
        ->assertJson(
            fn (AssertableJson $json) => $json->where('name', $attributes['name'])
                ->where('name', $attributes['name'])
                ->where('last_name', $attributes['last_name'])
                ->where('img_profile', $attributes['profile_img_url'])
                ->where('abbreviation', $attributes['abbreviation'])
                ->where('email', $attributes['email'])
                ->etc()
        );

    Mail::assertSent(CredentialsUserMail::class);
});

test('user cannot create users with incorrect data', function () {
    seed(PermissionSeeder::class);

    actingAs(User::factory()->create()->givePermissionTo('create-users'))
        ->postJson(route('api.users.create'))
        ->assertUnprocessable()
        ->assertJson(
            fn (AssertableJson $json) => $json->has('message')
                ->has('errors')
                ->whereType('errors', 'array')
                ->whereType('message', 'string')
                ->has('errors', fn (AssertableJson $json) => $json->hasAll(['name', 'last_name', 'email', 'role']))
                ->has('message')
                ->etc()
        );
});
