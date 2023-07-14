<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Board;
use App\Models\Stage;
use App\Models\Task;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use WithFaker, DatabaseMigrations;

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
}
