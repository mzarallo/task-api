<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
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
}
