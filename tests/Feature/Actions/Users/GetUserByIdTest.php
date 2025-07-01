<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Users;

use App\Actions\Users\GetUserById;
use App\Data\Services\Users\GetUserByIdServiceDto;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GetUserByIdTest extends TestCase
{
    use WithFaker;

    #[Test]
    public function it_get_user_by_id(): void
    {
        $user = User::factory()->create();

        $response = GetUserById::make()->handle(
            GetUserByIdServiceDto::from([
                'user_id' => $user->id,
            ])
        );

        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals($user->id, $response->id);
    }

    /**
     * @test
     */
    public function it_get_user_by_id_with_relations_loaded(): void
    {

        $user = User::factory()->create();

        $response = GetUserById::make()->handle(
            GetUserByIdServiceDto::from([
                'user_id' => $user->id,
                'relations' => ['boards'],
            ])
        );

        $this->assertTrue($response->relationLoaded('boards'));
    }
}
