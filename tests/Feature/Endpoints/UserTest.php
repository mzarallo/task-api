<?php

declare(strict_types=1);

namespace Tests\Feature\Endpoints;

use App\Mail\CredentialsUserMail;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    #[Test]
    public function user_can_get_all_users_paginated(): void
    {
        $this->seed(PermissionSeeder::class);
        User::factory()->count(2)->create();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('list-users'))
            ->getJson(route('api.users.all'));

        $response->assertJson(
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
        )->assertOk();
    }

    #[Test]
    public function user_cannot_get_users_without_authorization(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->getJson(route('api.users.all'));

        $response->assertForbidden();
    }

    #[Test]
    public function user_can_get_single_user_by_id(): void
    {
        $this->seed(PermissionSeeder::class);
        $user = User::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('list-users'))
            ->getJson(route('api.users.getById', $user));

        $response->assertJson(
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
        )->assertOk();
    }

    #[Test]
    public function user_gets_404_error_when_he_wants_to_get_a_user_that_does_not_exist(): void
    {
        $this->seed(PermissionSeeder::class);

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('list-users'))
            ->getJson(route('api.users.getById', ['user' => 99]));

        $response->assertNotFound();
    }

    #[Test]
    public function user_cannot_get_single_user_by_id_without_permission(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->getJson(route('api.users.getById', $user));

        $response->assertForbidden();
    }

    #[Test]
    public function user_can_delete_user_by_id(): void
    {
        $this->seed(PermissionSeeder::class);
        $user = User::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('delete-users'))
            ->deleteJson(route('api.users.deleteById', $user));

        $response->assertNoContent();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    #[Test]
    public function user_cannot_delete_user_by_id_without_permissions(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->deleteJson(route('api.users.deleteById', $user));

        $response->assertForbidden();
    }

    #[Test]
    public function user_gets_404_error_when_he_wants_to_delete_a_user_that_does_not_exist(): void
    {
        $this->seed(PermissionSeeder::class);

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('delete-users'))
            ->deleteJson(route('api.users.deleteById', ['user' => 99]));

        $response->assertStatus(404);
    }

    #[Test]
    public function user_can_update_users(): void
    {
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
        $user = User::factory()->create();
        $params = $this->getAttributes();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('edit-users'))
            ->patchJson(route('api.users.updateById', $user), $params->only(['name', 'last_name'])->toArray());

        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->where('name', $params->get('name'))
                ->where('last_name', $params->get('last_name'))
                ->where('img_profile', $user->profile_img_url)
                ->etc()
        )->assertOk();
    }

    private function getAttributes(): Collection
    {
        $name = $this->faker->name();
        $lastName = $this->faker->name();

        return Collection::make([
            'name' => $name,
            'last_name' => $lastName,
            'profile_img_url' => $this->faker->url(),
            'email' => $this->faker->email(),
            'role' => Role::all()->random()->name,
            'abbreviation' => Str::upper(Str::substr($name, 0, 1).Str::substr($lastName, 0, 1)),
        ]);
    }

    #[Test]
    public function user_cannot_update_users_without_permissions(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->patchJson(route('api.users.updateById', $user), []);

        $response->assertForbidden();
    }

    #[Test]
    public function user_gets_404_error_when_he_wants_update_a_user_that_does_not_exist(): void
    {
        $this->seed(PermissionSeeder::class);

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('edit-users'))
            ->patchJson(route('api.users.updateById', ['user' => 99]));

        $response->assertNotFound();
    }

    #[Test]
    public function user_can_create_users(): void
    {
        Mail::fake();
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);
        $attributes = $this->getAttributes();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('create-users'))
            ->postJson(route('api.users.create', $attributes->toArray()));

        $response->assertJson(
            fn (AssertableJson $json) => $json->where('name', $attributes->get('name'))
                ->where('name', $attributes->get('name'))
                ->where('last_name', $attributes->get('last_name'))
                ->where('img_profile', $attributes->get('profile_img_url'))
                ->where('abbreviation', $attributes->get('abbreviation'))
                ->where('email', $attributes->get('email'))
                ->etc()
        )->assertCreated();
        Mail::assertSent(CredentialsUserMail::class);
    }

    #[Test]
    public function user_cannot_create_users_with_incorrect_data(): void
    {
        $this->seed(PermissionSeeder::class);

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('create-users'))
            ->postJson(route('api.users.create', []));

        $response->assertJson(
            fn (AssertableJson $json) => $json->has('message')
                ->has('errors')
                ->whereType('errors', 'array')
                ->whereType('message', 'string')
                ->has('errors', fn (AssertableJson $json) => $json->hasAll(['name', 'last_name', 'email', 'role']))
                ->has('message')
                ->etc()
        )->assertUnprocessable();
    }
}
