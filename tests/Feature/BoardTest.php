<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Board;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BoardTest extends TestCase
{
    use WithFaker;
    /**
     * @test
     */
    public function user_can_get_all_boards(): void
    {
        $this->actingAs(User::find(1));
        $this->withoutExceptionHandling();

        $response = $this->json('GET', route('api.boards.all'));

        $response->assertJson(fn (AssertableJson $json) => $json
            ->has('links', fn (AssertableJson $json) => $json->hasAll('first', 'last', 'prev', 'next'))
            ->has('meta', fn (AssertableJson $json) => $json->hasAll('current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total')
            ->has('links.0', fn (AssertableJson $json) => $json->hasAll('url', 'label', 'active')))
            ->has('data.0', fn (AssertableJson $json) => $json->hasAll('id', 'name', 'hex_color', 'author_id', 'author_full_name')
            ->whereAllType([
                'id' => 'integer',
                'name' => 'string',
                'hex_color' => 'string',
                'author_id' => 'integer',
                'author_full_name' => 'string',
            ])
            )
        )->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_cannot_get_boards_without_authorization(): void
    {
        $this->actingAs(User::find(10));

        $response = $this->json('GET', route('api.boards.all'));

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_can_get_single_board_by_id(): void
    {
        $this->actingAs(User::find(1));
        $board = Board::all()->random();

        $response = $this->json('GET', route('api.boards.getById', ['id' => $board->id]));

        $response->assertJson(fn (AssertableJson $json) => $json->has('data', fn (AssertableJson $json) => $json->hasAll('id', 'name', 'hex_color', 'author_id', 'author_full_name')
            ->whereAllType([
                'id' => 'integer',
                'name' => 'string',
                'hex_color' => 'string',
                'author_id' => 'integer',
                'author_full_name' => 'string',
            ])
            ->where('id', $board->id)
            ->where('name', $board->name)
            ->where('hex_color', $board->hex_color)
            ->where('author_id', $board->author_id)
        )
        )->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_gets_404_error_when_he_wants_to_get_a_board_that_does_not_exist(): void
    {
        $this->actingAs(User::find(1));
        $this->withoutExceptionHandling();

        $response = $this->json('GET', route('api.boards.getById', ['id' => 99]));

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function user_cannot_get_single_board_by_id_without_permission(): void
    {
        $this->actingAs(User::find(10));
        $board = Board::all()->random();

        $response = $this->json('GET', route('api.boards.getById', ['id' => $board->id]));

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_can_delete_board_by_id(): void
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::find(1));
        $board = Board::all()->random();

        $response = $this->json('DELETE', route('api.boards.deleteById', ['id' => $board->id]));
        $response->assertStatus(204);

        $response = $this->json('GET', route('api.boards.getById', ['id' => $board->id]));
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function user_cannot_delete_board_by_id_without_permissions(): void
    {
        $this->actingAs(User::find(10));
        $board = Board::all()->random();

        $response = $this->json('DELETE', route('api.boards.deleteById', ['id' => $board->id]));
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_gets_404_error_when_he_wants_to_delete_a_board_that_does_not_exist(): void
    {
        $this->actingAs(User::find(1));
        $this->withoutExceptionHandling();

        $response = $this->json('GET', route('api.boards.getById', ['id' => 99]));

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function user_can_update_boards(): void
    {
        $this->actingAs(User::find(1));
        $board = Board::all()->random();

        $name = $this->faker->name();
        $hexColor = $this->faker->hexColor();

        $response = $this->json('PATCH', route('api.boards.updateById', ['id' => $board->id]), [
            'name' => $name,
            'hex_color' => $hexColor,
        ]);

        $response->assertJson(fn (AssertableJson $json) => $json->where('name', $name)
            ->where('hex_color', $hexColor)
            ->etc()
        )->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_cannot_update_boards_without_permissions(): void
    {
        $this->actingAs(User::find(10));
        $board = Board::all()->random();

        $name = $this->faker->name();
        $hexColor = $this->faker->hexColor();

        $response = $this->json('PATCH', route('api.boards.updateById', ['id' => $board->id]), [
            'name' => $name,
            'last_name' => $hexColor,
        ]);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_gets_404_error_when_he_wants_update_a_board_that_does_not_exist(): void
    {
        $this->actingAs(User::find(1));

        $response = $this->json('PATCH', route('api.boards.updateById', ['id' => 99]));

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function user_can_create_boards(): void
    {
        $this->actingAs(User::find(1));

        $name = $this->faker->name();
        $hexColor = $this->faker->hexColor();

        $response = $this->json('POST', route('api.boards.create', [
            'name' => $name,
            'hex_color' => $hexColor,
        ]));



        $response->assertJson(fn (AssertableJson $json) => $json->where('name', $name)
            ->where('hex_color', $hexColor)
            ->etc()
        )->assertStatus(201);
    }

    /**
     * @test
     */
    public function user_cannot_create_boards_with_incorrect_data(): void
    {
        $this->actingAs(User::find(1));

        $response = $this->json('POST', route('api.boards.create', []));

        $response->assertJson(fn (AssertableJson $json) => $json->has('message')
            ->has('errors')
            ->whereType('errors', 'array')
            ->whereType('message', 'string')
            ->has('errors', fn (AssertableJson $json) => $json->hasAll(['name', 'hex_color']))
            ->where('message', 'The given data was invalid.')
            ->etc()
        )->assertStatus(422);
    }
}
