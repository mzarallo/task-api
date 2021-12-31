<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    public function test_user_can_obtain_all_permissions()
    {
        $this->actingAs(User::all()->first());

        $response = $this->json('GET', route('api.permissions.all'));

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 21)->has('data.0', fn (AssertableJson $json) =>
                $json->hasAll('id', 'name', 'category', 'guard', 'created_at', 'updated_at')
                    ->whereAllType([
                        'id' => 'integer',
                        'name' => 'string',
                        'category' => 'string',
                        'guard' => 'string',
                        'created_at' => 'string',
                        'updated_at' => 'string'
                    ])
            )
        )->assertStatus(200);
    }

    public function test_user_cannot_get_permissions_without_authorization()
    {
        $this->actingAs(User::find(2));

        $response = $this->json('GET', route('api.permissions.all'));

        $response->assertStatus(403);
    }
}
