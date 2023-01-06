<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function user_can_obtain_all_roles(): void
    {
        $this->seed(RoleSeeder::class);
        $this->seed(PermissionSeeder::class);

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('list-roles'))
            ->getJson(route('api.roles.all'));

        $response->assertJson(
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
        )->assertOk();
    }

    /**
     * @test
     */
    public function user_cannot_get_roles_without_authorization(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->json('GET', route('api.roles.all'));

        $response->assertForbidden();
    }
}
