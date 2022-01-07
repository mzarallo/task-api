<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RoleTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_obtain_all_roles(): void
    {
        $this->actingAs(User::find(1));
        $this->withoutExceptionHandling();

        $response = $this->json('GET', route('api.roles.all'));

        $response->assertJson(fn (AssertableJson $json) => $json->has('data.0', fn (AssertableJson $json) => $json->hasAll('id', 'name', 'guard', 'created_at', 'updated_at')
            ->whereAllType([
                'id' => 'integer',
                'name' => 'string',
                'guard' => 'string',
                'created_at' => 'string',
                'updated_at' => 'string',
            ])
            )
        )->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_cannot_get_roles_without_authorization(): void
    {
        $this->actingAs(User::find(2));

        $response = $this->json('GET', route('api.roles.all'));

        $response->assertStatus(403);
    }
}
