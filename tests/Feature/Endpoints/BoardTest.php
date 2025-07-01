<?php

declare(strict_types=1);

use App\Models\Board;
use App\Models\User;
use App\Notifications\Boards\DownloadedBoard;
use Database\Seeders\PermissionSeeder;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

uses(\Illuminate\Foundation\Testing\DatabaseMigrations::class);
uses(\Illuminate\Foundation\Testing\WithFaker::class);

test('user can get all boards', function () {
    seed(PermissionSeeder::class);
    Board::factory()->count(2)->create();

    actingAs(
        User::factory()->create()->givePermissionTo('list-boards')
    )->getJson(route('api.boards.all'))
        ->assertOk()
        ->assertJson(
            fn (AssertableJson $json) => $json
                ->has('links', fn (AssertableJson $json) => $json->hasAll('first', 'last', 'prev', 'next'))
                ->has('meta', fn (AssertableJson $json) => $json->hasAll('current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total')
                    ->has('links.0', fn (AssertableJson $json) => $json->hasAll('url', 'label', 'active')))
                ->has(
                    'data',
                    2,
                    fn (AssertableJson $json) => $json->hasAll('id', 'name', 'hex_color', 'author_id', 'author_full_name')
                        ->whereAllType([
                            'id' => 'integer',
                            'name' => 'string',
                            'hex_color' => 'string',
                            'author_id' => 'integer',
                            'author_full_name' => 'string',
                        ])
                )
        );
});

test('user cannot get boards without authorization', function () {
    actingAs(User::factory()->create())
        ->getJson(route('api.boards.all'))
        ->assertForbidden();
});

test('user can get single board by id', function () {
    seed(PermissionSeeder::class);
    $board = Board::factory()->create();

    actingAs(User::factory()->create()->givePermissionTo('list-boards'))
        ->getJson(route('api.boards.getById', [$board]))
        ->assertOk()
        ->assertJson(
            fn (AssertableJson $json) => $json->has(
                'data',
                fn (AssertableJson $json) => $json->hasAll('id', 'name', 'hex_color', 'author_id', 'author_full_name')
                    ->whereAllType([
                        'id' => 'integer',
                        'name' => 'string',
                        'hex_color' => 'string',
                        'author_id' => 'integer',
                        'author_full_name' => 'string',
                    ])->whereAll(
                        collect($board->getAttributes())->only([
                            'name', 'hex_color', 'author_id', 'author_full_name',
                        ])->toArray()
                    )
            )
        );
});

test('user gets 404 error when he wants to get a board that does not exist', function () {
    seed(PermissionSeeder::class);

    actingAs(User::factory()->create()->givePermissionTo('list-boards'))
        ->getJson(route('api.boards.getById', ['board' => 99]))
        ->assertNotFound();
});

test('user cannot get single board by id without permission', function () {
    actingAs(User::factory()->create())
        ->getJson(route('api.boards.getById', [Board::factory()->create()]))
        ->assertForbidden();
});

test('user can delete board by id', function () {
    seed(PermissionSeeder::class);
    $board = Board::factory()->create();

    actingAs(User::factory()->create()->givePermissionTo('delete-boards'))
        ->deleteJson(route('api.boards.deleteById', [$board]))
        ->assertNoContent();
});

test('user cannot delete board by id without permissions', function () {
    actingAs(User::factory()->create())
        ->deleteJson(route('api.boards.deleteById', [Board::factory()->create()]))
        ->assertForbidden();
});

test('user gets 404 error when he wants to delete a board that does not exist', function () {
    seed(PermissionSeeder::class);

    actingAs(User::factory()->create()->givePermissionTo('delete-boards'))
        ->deleteJson(route('api.boards.deleteById', ['board' => 99]))
        ->assertNotFound();
});

test('user can update boards', function () {
    seed(PermissionSeeder::class);
    $params = [
        'name' => $this->faker->name,
        'hex_color' => $this->faker->hexColor(),
    ];

    actingAs(User::factory()->create()->givePermissionTo('edit-boards'))
        ->patchJson(route('api.boards.updateById', [Board::factory()->create()]), $params)
        ->assertOk()
        ->assertJson(
            fn (AssertableJson $json) => $json->whereAll($params)
                ->etc()
        );
});

test('user cannot update boards without permissions', function () {
    $params = [
        'name' => $this->faker->name,
        'hex_color' => $this->faker->hexColor(),
    ];

    actingAs(User::factory()->create())
        ->patchJson(route('api.boards.updateById', [Board::factory()->create()]), $params)
        ->assertForbidden();
});

test('user gets 404 error when he wants update a board that does not exist', function () {
    seed(PermissionSeeder::class);

    actingAs(User::factory()->create()->givePermissionTo('edit-boards'))
        ->patchJson(route('api.boards.updateById', ['board' => 99]))
        ->assertNotFound();
});

test('user can create boards', function () {
    seed(PermissionSeeder::class);
    $params = [
        'name' => $this->faker->name,
        'hex_color' => $this->faker->hexColor(),
    ];

    actingAs(User::factory()->create()->givePermissionTo('create-boards'))
        ->postJson(route('api.boards.create'), $params)
        ->assertCreated()
        ->assertJson(
            fn (AssertableJson $json) => $json->whereAll($params)
                ->etc()
        );
});

test('user cannot create boards with incorrect data', function () {
    seed(PermissionSeeder::class);

    actingAs(User::factory()->create()->givePermissionTo('create-boards'))
        ->postJson(route('api.boards.create'))
        ->assertUnprocessable()
        ->assertJson(
            fn (AssertableJson $json) => $json->has('message')
                ->has('errors')
                ->whereType('errors', 'array')
                ->whereType('message', 'string')
                ->has('errors', fn (AssertableJson $json) => $json->hasAll(['name', 'hex_color']))
                ->has('message')
                ->etc()
        );
});

test('send board as xls and pdf by mail notification', function (string $format) {
    Notification::fake();
    seed(PermissionSeeder::class);
    $board = Board::factory()->create();
    $user = User::factory()->create()->givePermissionTo('download-boards');

    actingAs($user)
        ->getJson(route('api.boards.download', ['format' => $format, $board]))
        ->assertAccepted();

    Notification::assertSentToTimes($user, DownloadedBoard::class);
})->with([
    ['format' => 'xls'],
    ['format' => 'pdf'],
]);

test('send board by mail is validated', function () {
    Notification::fake();
    seed(PermissionSeeder::class);
    $board = Board::factory()->create();
    $user = User::factory()->create()->givePermissionTo('download-boards');

    actingAs($user)
        ->getJson(route('api.boards.download', [$board]))
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('message')
            ->has('errors', fn (AssertableJson $json) => $json->has('format'))
        )->assertUnprocessable();

    Notification::assertNothingSent();
});
