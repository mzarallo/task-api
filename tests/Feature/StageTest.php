<?php

namespace Tests\Feature;

use App\Models\Board;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StageTest extends TestCase
{
    use WithFaker;
    /**
     * @test
     */
    public function user_can_get_all_stages(): void
    {
        $this->actingAs(User::find(1));
        $this->withoutExceptionHandling();
        $board = Board::all()->random();

        $response = $this->json('GET', route('api.boards.stages.all', ['boardId' => $board]));

        $response->assertJson(fn (AssertableJson $json) => $json
            ->has('links', fn (AssertableJson $json) => $json->hasAll('first', 'last', 'prev', 'next'))
            ->has('meta', fn (AssertableJson $json) => $json->hasAll('current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total')
                ->has('links.0', fn (AssertableJson $json) => $json->hasAll('url', 'label', 'active')))
            ->has('data.0', fn (AssertableJson $json) => $json->hasAll('id', 'name', 'slug', 'hex_color', 'order', 'is_final_stage', 'author_full_name')
                ->whereAllType([
                    'id' => 'integer',
                    'name' => 'string',
                    'slug' => 'string',
                    'hex_color' => 'string',
                    'order' => 'integer',
                    'is_final_stage' => 'boolean',
                    'author_full_name' => 'string',
                ])
                ->etc()
            )
        )->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_cannot_get_stages_without_authorization(): void
    {
        $this->actingAs(User::find(10));
        $stage = Stage::all()->random();

        $response = $this->json('GET', route('api.boards.stages.all', ['boardId' => $stage->board_id, 'stageId' => $stage]));

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_can_get_single_stage_by_id(): void
    {
        $this->actingAs(User::find(1));
        $stage = Stage::with(['author'])->get()->random();

        $response = $this->json('GET', route('api.boards.stages.getById', ['boardId' => $stage->board_id, 'stageId' => $stage]));

        $response->assertJson(fn (AssertableJson $json) => $json->has('data', fn (AssertableJson $json) =>
            $json->hasAll('id', 'name', 'slug', 'hex_color', 'order', 'is_final_stage', 'author_full_name')
                ->whereAllType([
                    'id' => 'integer',
                    'name' => 'string',
                    'slug' => 'string',
                    'hex_color' => 'string',
                    'order' => 'integer',
                    'is_final_stage' => 'boolean',
                    'author_full_name' => 'string',
                ])
                ->etc()
                ->where('id', $stage->id)
                ->where('name', $stage->name)
                ->where('slug', $stage->slug)
                ->where('hex_color', $stage->hex_color)
                ->where('order', $stage->order)
                ->where('is_final_stage', $stage->is_final_stage)
                ->where('author_full_name', $stage->author_full_name)
        )
        )->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_gets_404_error_when_he_wants_to_get_a_stage_that_does_not_exist(): void
    {
        $this->actingAs(User::find(1));
        $this->withoutExceptionHandling();

        $response = $this->json('GET', route('api.boards.stages.getById', ['boardId' => 99, 'stageId' => 99]));

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function user_cannot_get_single_stage_by_id_without_permission(): void
    {
        $this->actingAs(User::find(10));
        $stage = Stage::all()->random();

        $response = $this->json('GET', route('api.boards.stages.getById', ['boardId' => $stage->board_id, 'stageId' => $stage]));

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_can_delete_stage_by_id(): void
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::find(1));
        $stage = Stage::all()->random();

        $response = $this->json('DELETE', route('api.boards.stages.deleteById',
            [
                'boardId' => $stage->board_id,
                'stageId' => $stage->id
            ]
        ));
        $response->assertStatus(204);

        $response = $this->json('GET', route('api.boards.stages.getById',
            [
                'boardId' => $stage->board_id,
                'stageId' => $stage->id
            ]
        ));
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function user_cannot_delete_stage_by_id_without_permissions(): void
    {
        $this->actingAs(User::find(10));
        $stage = Stage::all()->random();

        $response = $this->json('DELETE', route('api.boards.stages.deleteById',
            [
                'boardId' => $stage->board_id,
                'stageId' => $stage->id
            ]
        ));
        $response->assertStatus(403);
    }
}
