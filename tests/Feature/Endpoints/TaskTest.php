<?php

declare(strict_types=1);

use App\Models\Board;
use App\Models\Stage;
use App\Models\Task;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Faker\fake;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\seed;

uses(DatabaseMigrations::class);

test('user can get all tasks of an specific stage', function () {
    seed(PermissionSeeder::class);
    $board = Board::factory()->has(Stage::factory()->has(Task::factory()->count(3)))->create();

    actingAs(User::factory()->create()->givePermissionTo('list-tasks'))
        ->getJson(route('api.boards.stages.tasks.all', [$board, $board->stages->first()]))
        ->assertOk()
        ->assertJson(
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
        );
});

test('user cannot get tasks without authorization', function () {
    $board = Board::factory()->has(Stage::factory()->has(Task::factory()->count(3)))->create();

    $response = $this
        ->actingAs(User::factory()->create())
        ->getJson(route('api.boards.stages.tasks.all', [$board, $board->stages->first()]));

    $response->assertForbidden();
});

test('user cannot list tasks if route params are not related', function () {
    seed(PermissionSeeder::class);
    $board = Board::factory()->has(Stage::factory()->has(Task::factory()->count(3)))->create();
    $board2 = Board::factory()->has(Stage::factory()->has(Task::factory()->count(3)))->create();

    actingAs(User::factory()->create()->givePermissionTo('list-tasks'))
        ->getJson(route('api.boards.stages.tasks.all', [$board2, $board->stages->first()]))
        ->assertNotFound();
});

it('user can create task', function () {
    seed(PermissionSeeder::class);
    $stage = Stage::factory()->create();
    $attributes = [
        'title' => fake()->sentence,
        'description' => fake()->realText,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addWeek()->toDateString(),
        'tags' => [fake()->word, fake()->word],
        'order' => fake()->randomNumber(),
    ];

    actingAs(User::factory()->create()->givePermissionTo('create-tasks'))
        ->postJson(route('api.boards.stages.tasks.create', [$stage->board, $stage]), $attributes)
        ->assertCreated()
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->where('title', $attributes['title'])
                    ->where('description', $attributes['description'])
                    ->where('start_date', $attributes['start_date'])
                    ->where('end_date', $attributes['end_date'])
                    ->where('tags', $attributes['tags'])
                    ->where('order', $attributes['order'])
                    ->etc()
                )
        );
});

test('cannot create task with incorrect data', function () {
    seed(PermissionSeeder::class);
    $stage = Stage::factory()->create();

    actingAs(User::factory()->create()->givePermissionTo('create-tasks'))
        ->postJson(route('api.boards.stages.tasks.create', [$stage->board, $stage]))
        ->assertUnprocessable()->assertJson(
            fn (AssertableJson $json) => $json->has('message')
                ->has('errors')
                ->has('message')
                ->whereType('errors', 'array')
                ->whereType('message', 'string')
                ->has('errors', fn (AssertableJson $json) => $json->hasAll([
                    'title',
                    'start_date',
                ]))->etc()
        );
});

it('user cannot create task without permissions', function () {
    seed(PermissionSeeder::class);
    $stage = Stage::factory()->create();
    $attributes = [
        'title' => fake()->sentence,
        'description' => fake()->realText,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addWeek()->toDateString(),
        'tags' => [fake()->word, fake()->word],
        'order' => fake()->randomNumber(),
    ];

    actingAs(User::factory()->create())
        ->postJson(route('api.boards.stages.tasks.create', [$stage->board, $stage]), $attributes)
        ->assertForbidden();
});

test('user can delete task by id', function () {
    seed(PermissionSeeder::class);
    $task = Task::factory()->create();

    actingAs(User::factory()->create()->givePermissionTo('delete-tasks'))
        ->deleteJson(
            route('api.boards.stages.tasks.deleteById', [$task->stage->board, $task->stage, $task])
        )->assertNoContent();

    assertDatabaseMissing('tasks', ['id' => $task->id]);
});

test('user cannot delete tasks by id without permissions', function () {
    $task = Task::factory()->create();

    actingAs(User::factory()->create())->deleteJson(
        route('api.boards.stages.tasks.deleteById', [$task->stage->board, $task->stage, $task])
    )->assertForbidden();
});

test('user can update tasks', function () {
    seed(PermissionSeeder::class);
    $task = Task::factory()
        ->state(['start_date' => now()->subWeeks(2), 'end_date' => now()->subWeek()])
        ->create();
    $attributes = [
        'title' => fake()->sentence,
        'description' => fake()->realText,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addWeek()->toDateString(),

    ];

    actingAs(User::factory()->create()->givePermissionTo('edit-tasks'))
        ->patchJson(
            route('api.boards.stages.tasks.updateById', [$task->stage->board, $task->stage, $task]),
            $attributes
        )->assertOk()
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->where('title', $attributes['title'])
                ->where('description', $attributes['description'])
                ->where('start_date', $attributes['start_date'])
                ->where('end_date', $attributes['end_date'])
                ->etc()
        );
});

test('user cannot update tasks without permissions', function () {
    $task = Task::factory()->create();

    actingAs(User::factory()->create())
        ->patchJson(route('api.boards.stages.tasks.updateById', [$task->stage->board, $task->stage, $task]))
        ->assertForbidden();
});
