<?php

declare(strict_types=1);

namespace Tests\Feature\Endpoints;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function user_can_obtain_all_permissions(): void
    {
        $this->seed(PermissionSeeder::class);

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('list-permissions'))
            ->getJson(route('api.permissions.all'));

        $response->assertJson(
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
        )->assertOk();
    }

    /**
     * @test
     */
    public function user_cannot_get_permissions_without_authorization(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->getJson(route('api.permissions.all'));

        $response->assertForbidden();
    }
}
