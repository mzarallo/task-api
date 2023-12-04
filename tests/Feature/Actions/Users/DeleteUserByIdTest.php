<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Users;

use App\Actions\Users\DeleteUserById;
use App\Data\Services\Users\DeleteUserByIdServiceDto;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteUserByIdTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function it_delete_user_by_id(): void
    {
        $user = User::factory()->create();

        $response = DeleteUserById::make()->handle(
            DeleteUserByIdServiceDto::from([
                'user_id' => $user->id,
            ])
        );

        $this->assertTrue($response);
        $this->assertDatabaseEmpty('users');
    }

    /**
     * @test
     */
    public function it_throws_an_exception_for_user_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        DeleteUserById::make()->handle(
            DeleteUserByIdServiceDto::from([
                'user_id' => $this->faker->randomNumber(),
            ])
        );
    }
}
