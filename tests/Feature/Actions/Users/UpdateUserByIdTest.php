<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Users;

use App\Actions\Users\UpdateUserById;
use App\Data\Services\Users\UpdateUserByIdServiceDto;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateUserByIdTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function it_update_user_by_id(): void
    {
        $user = User::factory()->create();
        $params = [
            'name' => $this->faker->name,
            'last_name' => $this->faker->lastName,
        ];

        $response = UpdateUserById::make()->handle(
            $user->id,
            UpdateUserByIdServiceDto::from($params)
        );

        $this->assertInstanceOf(User::class, $response);
        $this->assertDatabaseHas('users', $params);
    }
}
