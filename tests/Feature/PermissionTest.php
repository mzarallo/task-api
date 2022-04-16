<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function user_can_obtain_all_permissions(): void
    {
        $this->actingAs(User::find(1));

        $response = $this->json('GET', route('api.permissions.all'));

        $response->assertJson(
            fn (AssertableJson $json) => $json->has(
                'data.0',
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
        )->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_cannot_get_permissions_without_authorization(): void
    {
        $this->actingAs(User::find(2));

        $response = $this->json('GET', route('api.permissions.all'));

        $response->assertStatus(403);
    }
}
