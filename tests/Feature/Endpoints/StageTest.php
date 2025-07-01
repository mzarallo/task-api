<?php

declare(strict_types=1);

use App\Models\Board;
use App\Models\Stage;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\getJson;
use function Pest\Laravel\seed;

uses(WithFaker::class);
uses(DatabaseMigrations::class);

test('user can get all stages', function () {
    seed(PermissionSeeder::class);
    $board = Board::factory()->has(Stage::factory()->count(3))->create();

    actingAs(User::factory()->create()->givePermissionTo('list-stages'))
        ->getJson(route('api.boards.stages.all', $board))
        ->assertOk()
        ->assertJson(
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
        );
});

test('user cannot get stages without authorization', function () {
    actingAs(User::factory()->create());
    $board = Board::factory()->create();

    getJson(route('api.boards.stages.all', $board))
        ->assertForbidden();
});

test('user can get single stage by id', function () {
    seed(PermissionSeeder::class);
    $stage = Stage::factory()->create();

    actingAs(User::factory()->create()->givePermissionTo('list-stages'))
        ->getJson(route('api.boards.stages.getById', [$stage->board, $stage]))
        ->assertOk()
        ->assertJson(
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
        );
});

test('user gets 404 error when he wants to get a stage that does not exist', function () {
    seed(PermissionSeeder::class);

    actingAs(User::factory()->create()->givePermissionTo('list-stages'))
        ->getJson(route('api.boards.stages.getById', ['board' => 99, 'stage' => 99]))
        ->assertNotFound();
});

test('user cannot get single stage by id without permission', function () {
    actingAs(User::factory()->create());
    $stage = Stage::factory()->create();

    getJson(route('api.boards.stages.getById', [$stage->board, $stage]))
        ->assertForbidden();
});

test('user can delete stage by id', function () {
    seed(PermissionSeeder::class);
    $stage = Stage::factory()->create();

    actingAs(User::factory()->create()->givePermissionTo('delete-stages'))
        ->deleteJson(route('api.boards.stages.deleteById', [$stage->board, $stage]))
        ->assertNoContent();

    assertDatabaseMissing('stages', ['id' => $stage->id]);
});

test('user cannot delete stage by id without permissions', function () {
    $stage = Stage::factory()->create();

    actingAs(User::factory()->create())
        ->deleteJson(route('api.boards.stages.deleteById', [$stage->board, $stage]))
        ->assertForbidden();
});

test('user can update stages', function () {
    seed(PermissionSeeder::class);
    $stage = Stage::factory()->create();
    $attributes = [
        'name' => $this->faker->randomElement(['Pending', 'In progress', 'Finished']),
        'hex_color' => $this->faker->hexColor,
    ];

    actingAs(User::factory()->create()->givePermissionTo('edit-stages'))
        ->patchJson(
            route('api.boards.stages.updateById', [$stage->board, $stage]),
            $attributes
        )->assertOk()
        ->assertJson(
            fn (AssertableJson $json) => $json->where('name', $attributes['name'])
                ->where('hex_color', $attributes['hex_color'])
                ->etc()
        );
});

test('user cannot update stages without permissions', function () {
    $stage = Stage::factory()->create();

    actingAs(User::factory()->create())
        ->patchJson(route('api.boards.stages.updateById', [$stage->board, $stage]))
        ->assertForbidden();
});

test('user can create stages', function () {
    seed(PermissionSeeder::class);
    $board = Board::factory()->create();

    actingAs(User::factory()->create()->givePermissionTo('create-stages'))
        ->postJson(
            route('api.boards.stages.create', $board),
            [
                'name' => 'Pending',
                'slug' => 'pending',
                'hex_color' => $this->faker->hexColor,
                'order' => $this->faker->randomNumber(2),
                'is_final_stage' => $this->faker->boolean,
                'board_id' => Board::factory()->create()->id,
                'author_id' => User::factory()->create()->id,
            ]
        )->assertCreated()
        ->assertJson(
            fn (AssertableJson $json) => $json->hasAll([
                'name',
                'slug',
                'hex_color',
                'order',
                'is_final_stage',
                'board_id',
                'author_id',
            ])->etc()
        );
});

test('user cannot create stages with incorrect data', function () {
    seed(PermissionSeeder::class);
    $board = Board::factory()->create();

    actingAs(User::factory()->create()->givePermissionTo('create-stages'))
        ->postJson(route('api.boards.stages.create', $board))
        ->assertUnprocessable()
        ->assertJson(
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
        );
});

test('user cannot create stages without permissions', function () {
    $board = Board::factory()->create();

    actingAs(User::factory()->create())
        ->postJson(route('api.boards.stages.create', $board))
        ->assertForbidden();
});
