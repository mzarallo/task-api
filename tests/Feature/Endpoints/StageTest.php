<?php

declare(strict_types=1);

namespace Tests\Feature\Endpoints;

use App\Models\Board;
use App\Models\Stage;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StageTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    #[Test]
    public function user_can_get_all_stages(): void
    {
        $this->seed(PermissionSeeder::class);
        $board = Board::factory()->has(Stage::factory()->count(3))->create();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('list-stages'))
            ->getJson(route('api.boards.stages.all', $board));

        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->has('links', fn (AssertableJson $json) => $json->hasAll('first', 'last', 'prev', 'next'))
                ->has('meta', fn (AssertableJson $json) => $json->hasAll('current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total')
                    ->has('links.0', fn (AssertableJson $json) => $json->hasAll('url', 'label', 'active')))
                ->has(
                    'data',
                    3,
                    fn (AssertableJson $json) => $json->hasAll('id', 'name', 'slug', 'hex_color', 'order', 'is_final_stage', 'author_full_name')
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
        )->assertOk();
    }

    #[Test]
    public function user_cannot_get_stages_without_authorization(): void
    {
        $this->actingAs(User::factory()->create());
        $board = Board::factory()->create();

        $response = $this->getJson(route('api.boards.stages.all', $board));

        $response->assertForbidden();
    }

    #[Test]
    public function user_can_get_single_stage_by_id(): void
    {
        $this->seed(PermissionSeeder::class);
        $stage = Stage::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('list-stages'))
            ->getJson(route('api.boards.stages.getById', [$stage->board, $stage]));

        $response->assertJson(
            fn (AssertableJson $json) => $json->has(
                'data',
                fn (AssertableJson $json) => $json->hasAll('id', 'name', 'slug', 'hex_color', 'order', 'is_final_stage', 'author_full_name')
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
        )->assertOk();
    }

    #[Test]
    public function user_gets_404_error_when_he_wants_to_get_a_stage_that_does_not_exist(): void
    {
        $this->seed(PermissionSeeder::class);

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('list-stages'))
            ->getJson(route('api.boards.stages.getById', ['board' => 99, 'stage' => 99]));

        $response->assertNotFound();
    }

    #[Test]
    public function user_cannot_get_single_stage_by_id_without_permission(): void
    {
        $this->actingAs(User::factory()->create());
        $stage = Stage::factory()->create();

        $response = $this->getJson(route('api.boards.stages.getById', [$stage->board, $stage]));

        $response->assertForbidden();
    }

    #[Test]
    public function user_can_delete_stage_by_id(): void
    {
        $this->seed(PermissionSeeder::class);
        $stage = Stage::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('delete-stages'))
            ->deleteJson(route('api.boards.stages.deleteById', [$stage->board, $stage]));

        $response->assertNoContent();
        $this->assertDatabaseMissing('stages', ['id' => $stage->id]);
    }

    #[Test]
    public function user_cannot_delete_stage_by_id_without_permissions(): void
    {
        $this->actingAs(User::factory()->create());
        $stage = Stage::factory()->create();

        $response = $this->deleteJson(route('api.boards.stages.deleteById', [$stage->board, $stage]));

        $response->assertForbidden();
    }

    #[Test]
    public function user_can_update_stages(): void
    {
        $this->seed(PermissionSeeder::class);
        $stage = Stage::factory()->create();
        $attributes = $this->getAttributes()->only(['name', 'hex_color']);

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('edit-stages'))
            ->patchJson(route('api.boards.stages.updateById', [$stage->board, $stage]), $attributes->toArray());

        $response->assertJson(
            fn (AssertableJson $json) => $json->where('name', $attributes->get('name'))
                ->where('hex_color', $attributes->get('hex_color'))
                ->etc()
        )->assertOk();
    }

    private function getAttributes(): Collection
    {
        $name = $this->faker->randomElement(['Pending', 'In progress', 'Finished']);

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

    #[Test]
    public function user_cannot_update_stages_without_permissions(): void
    {
        $this->actingAs(User::factory()->create());
        $stage = Stage::factory()->create();

        $response = $this->patchJson(route('api.boards.stages.updateById', [$stage->board, $stage]), []);

        $response->assertForbidden();
    }

    #[Test]
    public function user_can_create_stages(): void
    {
        $this->seed(PermissionSeeder::class);
        $board = Board::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('create-stages'))
            ->postJson(route('api.boards.stages.create', $board), $this->getAttributes()->toArray());

        $response->assertJson(
            fn (AssertableJson $json) => $json->hasAll([
                'name',
                'slug',
                'hex_color',
                'order',
                'is_final_stage',
                'board_id',
                'author_id',
            ])->etc()
        )->assertCreated();
    }

    #[Test]
    public function user_cannot_create_stages_with_incorrect_data(): void
    {
        $this->seed(PermissionSeeder::class);
        $board = Board::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('create-stages'))
            ->postJson(route('api.boards.stages.create', $board));

        $response->assertJson(
            fn (AssertableJson $json) => $json->has('message')
                ->has('errors')
                ->has('message')
                ->whereType('errors', 'array')
                ->whereType('message', 'string')
                ->has('errors', fn (AssertableJson $json) => $json->hasAll([
                    'name',
                    'hex_color',
                    'order',
                ]))
                ->etc()
        )->assertUnprocessable();
    }

    #[Test]
    public function user_cannot_create_stages_without_permissions(): void
    {
        $board = Board::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->postJson(route('api.boards.stages.create', $board), []);

        $response->assertForbidden();
    }
}
