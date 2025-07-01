<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Roles;

use App\Actions\Roles\AttachRoleToUser;
use App\Data\Services\Roles\AttachRoleToUserDto;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AttachRolesToUserTest extends TestCase
{
    use WithFaker;

    #[Test]
    public function it_attach_role_to_user(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();

        $response = AttachRoleToUser::make()->handle(
            $user->id,
            AttachRoleToUserDto::from([
                'role' => 'Administrator',
            ])
        );

        $this->assertInstanceOf(User::class, $response);
        $this->assertTrue($response->hasRole('Administrator'));
    }
}
