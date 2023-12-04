<?php

declare(strict_types=1);

namespace Tests\Feature\Endpoints;

use App\Models\Board;
use App\Models\Stage;
use App\Models\Task;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /**
     * @test
     */
    public function user_can_get_all_tasks_of_an_specific_stage(): void
    {
        $this->seed(PermissionSeeder::class);
        $board = Board::factory()->has(Stage::factory()->has(Task::factory()->count(3)))->create();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('list-tasks'))
            ->getJson(route('api.boards.stages.tasks.all', [$board, $board->stages->first()]));

        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->has('links', fn (AssertableJson $json) => $json->hasAll('first', 'last', 'prev', 'next'))
                ->has('meta', fn (AssertableJson $json) => $json->hasAll('current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total')
                    ->has('links.0', fn (AssertableJson $json) => $json->hasAll('url', 'label', 'active')))
                ->has(
                    'data',
                    3,
                    fn (AssertableJson $json) => $json->hasAll([
                        'id',
                        'title',
                        'description',
                        'start_date',
                        'end_date',
                        'tags',
                        'order',
                        'author',
                    ])->whereAllType([
                        'id' => 'integer',
                        'title' => 'string',
                        'description' => 'string',
                        'start_date' => 'null',
                        'end_date' => 'null',
                        'tags' => 'array',
                        'order' => 'integer',
                        'author' => 'array',
                    ])->etc()
                )
        )->assertOk();
    }

    /**
     * @test
     */
    public function user_cannot_get_tasks_without_authorization(): void
    {
        $board = Board::factory()->has(Stage::factory()->has(Task::factory()->count(3)))->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->getJson(route('api.boards.stages.tasks.all', [$board, $board->stages->first()]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function user_cannot_list_tasks_if_route_params_are_not_related(): void
    {
        $this->seed(PermissionSeeder::class);
        $board = Board::factory()->has(Stage::factory()->has(Task::factory()->count(3)))->create();
        $board2 = Board::factory()->has(Stage::factory()->has(Task::factory()->count(3)))->create();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('list-tasks'))
            ->getJson(route('api.boards.stages.tasks.all', [$board2, $board->stages->first()]));

        $response->assertNotFound();
    }

    public function user_gets_404_error_when_he_wants_to_get_a_board_that_does_not_exist(): void
    {
    }

    /** @test  */
    public function user_can_create_task(): void
    {
        $this->seed(PermissionSeeder::class);
        $stage = Stage::factory()->create();
        $attributes = $this->getAttributes();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('create-tasks'))
            ->postJson(route('api.boards.stages.tasks.create', [$stage->board, $stage]), $attributes->toArray());

        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->where('title', $attributes->get('title'))
                    ->where('description', $attributes->get('description'))
                    ->where('start_date', $attributes->get('start_date'))
                    ->where('end_date', $attributes->get('end_date'))
                    ->where('tags', $attributes->get('tags'))
                    ->where('order', $attributes->get('order'))
                    ->etc()
                )
        )->assertCreated();

    }

    private function getAttributes(): Collection
    {
        return Collection::make([
            'title' => $this->faker->sentence,
            'description' => $this->faker->realText,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addWeek()->toDateString(),
            'tags' => [$this->faker->word, $this->faker->word],
            'order' => $this->faker->randomNumber(),
        ]);
    }

    /** @test */
    public function cannot_create_task_with_incorrect_data(): void
    {
        $this->seed(PermissionSeeder::class);
        $stage = Stage::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('create-tasks'))
            ->postJson(route('api.boards.stages.tasks.create', [$stage->board, $stage]));

        $response->assertJson(
            fn (AssertableJson $json) => $json->has('message')
                ->has('errors')
                ->has('message')
                ->whereType('errors', 'array')
                ->whereType('message', 'string')
                ->has('errors', fn (AssertableJson $json) => $json->hasAll([
                    'title',
                    'start_date',
                ]))->etc()
        )->assertUnprocessable();
    }

    /** @test  */
    public function user_cannot_create_task_without_permissions(): void
    {
        $this->seed(PermissionSeeder::class);
        $stage = Stage::factory()->create();
        $attributes = $this->getAttributes();

        $response = $this
            ->actingAs(User::factory()->create())
            ->postJson(route('api.boards.stages.tasks.create', [$stage->board, $stage]), $attributes->toArray());

        $response->assertForbidden();
    }

    /** @test */
    public function user_can_delete_task_by_id(): void
    {
        $this->seed(PermissionSeeder::class);
        $task = Task::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('delete-tasks'))
            ->deleteJson(
                route('api.boards.stages.tasks.deleteById', [$task->stage->board, $task->stage, $task])
            );

        $response->assertNoContent();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /**
     * @test
     */
    public function user_cannot_delete_tasks_by_id_without_permissions(): void
    {
        $this->actingAs(User::factory()->create());
        $task = Task::factory()->create();

        $response = $this->deleteJson(
            route('api.boards.stages.tasks.deleteById', [$task->stage->board, $task->stage, $task])
        );

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function user_can_update_tasks(): void
    {
        $this->seed(PermissionSeeder::class);
        $task = Task::factory()
            ->state(['start_date' => now()->subWeeks(2), 'end_date' => now()->subWeek()])
            ->create();
        $attributes = $this->getAttributes()->only(['title', 'description', 'start_date', 'end_date']);

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('edit-tasks'))
            ->patchJson(
                route('api.boards.stages.tasks.updateById', [$task->stage->board, $task->stage, $task]),
                $attributes->toArray()
            );

        $response->assertJson(
            fn (AssertableJson $json) => $json->where('title', $attributes->get('title'))
                ->where('description', $attributes->get('description'))
                ->where('start_date', $attributes->get('start_date'))
                ->where('end_date', $attributes->get('end_date'))
                ->etc()
        )->assertOk();
    }

    /**
     * @test
     */
    public function user_cannot_update_tasks_without_permissions(): void
    {
        $this->actingAs(User::factory()->create());
        $task = Task::factory()->create();

        $response = $this->patchJson(route('api.boards.stages.tasks.updateById', [$task->stage->board, $task->stage, $task]));

        $response->assertForbidden();
    }
}
