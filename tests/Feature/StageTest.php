<?php

namespace Tests\Feature;

use App\Models\Board;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StageTest extends TestCase
{
    use WithFaker, DatabaseMigrations;
    /**
     * @test
     */
    public function user_can_get_all_stages(): void
    {
        $this->actingAs(User::find(1));
        $this->withoutExceptionHandling();
        $stage = Stage::factory()->create();

        $response = $this->json('GET', route('api.boards.stages.all', ['board' => $stage->board_id]));

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
        $stage = Stage::factory()->create();

        $response = $this->json('GET', route('api.boards.stages.all', ['board' => $stage->board_id, 'stage' => $stage]));

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_can_get_single_stage_by_id(): void
    {
        $this->actingAs(User::find(1));
        $stage = Stage::with(['author'])->get()->random();

        $response = $this->json('GET', route('api.boards.stages.getById', ['board' => $stage->board_id, 'stage' => $stage]));

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

        $response = $this->json('GET', route('api.boards.stages.getById', ['board' => 99, 'stage' => 99]));

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function user_cannot_get_single_stage_by_id_without_permission(): void
    {
        $this->actingAs(User::find(10));
        $stage = Stage::factory()->create();

        $response = $this->json('GET', route('api.boards.stages.getById', ['board' => $stage->board_id, 'stage' => $stage]));

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_can_delete_stage_by_id(): void
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::find(1));
        $stage = Stage::factory()->create();

        $response = $this->json('DELETE', route('api.boards.stages.deleteById',
            [
                'board' => $stage->board_id,
                'stage' => $stage->id
            ]
        ));
        $response->assertStatus(204);

        $response = $this->json('GET', route('api.boards.stages.getById',
            [
                'board' => $stage->board_id,
                'stage' => $stage->id
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
        $stage = Stage::factory()->create();

        $response = $this->json('DELETE', route('api.boards.stages.deleteById',
            [
                'board' => $stage->board_id,
                'stage' => $stage->id
            ]
        ));
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    /**
     * @test
     */
    public function user_can_update_stages(): void
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::find(1));
        $stage = Stage::factory()->create();
        $attributes = $this->getAttributes()->only(['name', 'hex_color']);

        $response = $this->json('PATCH', route('api.boards.stages.updateById', [
            'board' => $stage->board_id,
            'stage' => $stage->id
        ]), $attributes->toArray());


        $response->assertJson(fn (AssertableJson $json) => $json->where('name', $attributes->get('name'))
            ->where('hex_color', $attributes->get('hex_color'))
            ->etc()
        )->assertStatus(200);
    }

    /**
     * @test
     */
    public function user_cannot_update_stages_without_permissions(): void
    {
        $this->actingAs(User::find(10));
        $stage = Stage::factory()->create();

        $response = $this->json('PATCH', route('api.boards.stages.updateById', [
            'board' => $stage->board_id,
            'stage' => $stage->id
        ]), []);

        $response->assertStatus(403);
    }

    private function getAttributes(): Collection
    {
        $name = $this->faker->name;

        return Collection::make([
            'name' => $name,
            'slug' => Str::slug($name),
            'hex_color' => $this->faker->hexColor,
            'order' => $this->faker->randomNumber(2),
            'is_final_stage' => $this->faker->boolean,
            'board_id' => Board::factory()->create()->id,
            'author_id' => User::factory()->create()->id,
        ]);
    }
}
