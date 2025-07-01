<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Users;

use App\Actions\Users\CreateUser;
use App\Data\Services\Users\CreateUserServiceDto;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use WithFaker;

    #[Test]
    public function it_create_a_user(): void
    {
        Mail::fake();
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
