<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Users;

use App\Actions\Users\CreateUser;
use App\Data\Services\Users\CreateUserServiceDto;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function it_create_a_user(): void
    {
        Role::query()->create(['name' => 'Admin']);

        $response = CreateUser::make()->handle(
            CreateUserServiceDto::from([
                'name' => $this->faker->sentence,
                'last_name' => $this->faker->lastName,
                'email' => $this->faker->email,
                'role' => 'Admin',
            ])
        );

        $this->assertInstanceOf(User::class, $response);
    }
}
