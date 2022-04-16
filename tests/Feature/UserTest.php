<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\CredentialsUserMail;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function user_can_get_all_users(): void
    {
        $this->actingAs(User::find(1));
        $this->withoutExceptionHandling();

        $response = $this->json('GET', route('api.users.all'));

        $response->assertJson(fn (AssertableJson $json) => $json->has('data.0', fn (AssertableJson $json) => $json->hasAll('id', 'name', 'last_name', 'abbreviation', 'img_profile', 'email')
            ->whereAllType([
                'id' => 'integer',
                'name' => 'string',
                'last_name' => 'string',
                'abbreviation' => 'string',
                'img_profile' => 'string|null',
                'email' => 'string',
            ])
            )
        )->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_cannot_get_users_without_authorization(): void
    {
        $this->actingAs(User::find(2));

        $response = $this->json('GET', route('api.users.all'));

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_can_get_single_user_by_id(): void
    {
        $this->actingAs(User::find(1));
        $user = User::all()->random();

        $response = $this->json('GET', route('api.users.getById', ['id' => $user->id]));

        $response->assertJson(fn (AssertableJson $json) => $json->has('data', fn (AssertableJson $json) => $json->hasAll('id', 'name', 'last_name', 'abbreviation', 'img_profile', 'email')
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
        )->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_gets_404_error_when_he_wants_to_get_a_user_that_does_not_exist(): void
    {
        $this->actingAs(User::find(1));
        $this->withoutExceptionHandling();

        $response = $this->json('GET', route('api.users.getById', ['id' => 99]));

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function user_cannot_get_single_user_by_id_without_permission(): void
    {
        $this->actingAs(User::find(2));
        $user = User::all()->random();

        $response = $this->json('GET', route('api.users.getById', ['id' => $user->id]));

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_can_delete_user_by_id(): void
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::find(1));
        $user = User::all()->random();

        $response = $this->json('DELETE', route('api.users.deleteById', ['id' => $user->id]));
        $response->assertStatus(204);

        $response = $this->json('GET', route('api.users.getById', ['id' => $user->id]));
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function user_cannot_delete_user_by_id_without_permissions(): void
    {
        $this->actingAs(User::find(2));
        $user = User::all()->random();

        $response = $this->json('DELETE', route('api.users.deleteById', ['id' => $user->id]));
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_gets_404_error_when_he_wants_to_delete_a_user_that_does_not_exist(): void
    {
        $this->actingAs(User::find(1));
        $this->withoutExceptionHandling();

        $response = $this->json('GET', route('api.users.getById', ['id' => 99]));

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function user_can_update_users(): void
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::find(1));
        $user = User::all()->random();

        $name = $this->faker->name();
        $lastName = $this->faker->lastName();
        $imgUrl = $this->faker->url();
        $abbreviation = Str::upper(Str::substr($name, 0, 1).Str::substr($lastName, 0, 1));

        $response = $this->json('PATCH', route('api.users.updateById', ['id' => $user->id]), [
            'name' => $name,
            'last_name' => $lastName,
            'profile_img_url' => $imgUrl,
        ]);

        $response->assertJson(fn (AssertableJson $json) => $json->where('name', $name)
            ->where('name', $name)
            ->where('last_name', $lastName)
            ->where('img_profile', $imgUrl)
            ->where('abbreviation', $abbreviation)
            ->etc()
        )->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_cannot_update_users_without_permissions(): void
    {
        $this->actingAs(User::find(2));
        $user = User::all()->random();

        $name = $this->faker->name();
        $lastName = $this->faker->lastName();
        $imgUrl = $this->faker->url();
        $email = $this->faker->email();

        $response = $this->json('PATCH', route('api.users.updateById', ['id' => $user->id]), [
            'name' => $name,
            'last_name' => $lastName,
            'profile_img_url' => $imgUrl,
            'email' => $email,
        ]);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_gets_404_error_when_he_wants_update_a_user_that_does_not_exist(): void
    {
        $this->actingAs(User::find(1));
        $this->withoutExceptionHandling();

        $response = $this->json('PATCH', route('api.users.updateById', ['id' => 99]));

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function user_can_create_users(): void
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::find(1));

        $name = $this->faker->name();
        $lastName = $this->faker->lastName();
        $imgUrl = $this->faker->url();
        $email = $this->faker->email();
        $role = Role::all()->random();
        $abbreviation = Str::upper(Str::substr($name, 0, 1).Str::substr($lastName, 0, 1));

        Mail::fake();

        $response = $this->json('POST', route('api.users.create', [
            'name' => $name,
            'last_name' => $lastName,
            'profile_img_url' => $imgUrl,
            'email' => $email,
            'role' => $role->name,
        ]));

        Mail::assertSent(CredentialsUserMail::class);

        $response->assertJson(fn (AssertableJson $json) => $json->where('name', $name)
            ->where('name', $name)
            ->where('last_name', $lastName)
            ->where('img_profile', $imgUrl)
            ->where('abbreviation', $abbreviation)
            ->where('email', $email)
            ->etc()
        )->assertStatus(201);
    }

    /**
     * @test
     */
    public function user_cannot_create_users_with_incorrect_data(): void
    {
        $this->actingAs(User::find(1));

        $response = $this->json('POST', route('api.users.create', []));

        $response->assertJson(fn (AssertableJson $json) => $json->has('message')
            ->has('errors')
            ->whereType('errors', 'array')
            ->whereType('message', 'string')
            ->has('errors', fn (AssertableJson $json) => $json->hasAll(['name', 'last_name', 'email', 'role']))
            ->has('message')
            ->etc()
        )->assertStatus(422);
    }
}
