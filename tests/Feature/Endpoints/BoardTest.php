<?php

declare(strict_types=1);

namespace Tests\Feature\Endpoints;

use App\Models\Board;
use App\Models\User;
use App\Notifications\Boards\DownloadedBoard;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BoardTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    #[Test]
    public function user_can_get_all_boards(): void
    {
        $this->seed(PermissionSeeder::class);
        Board::factory()->count(2)->create();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('list-boards'))
            ->getJson(route('api.boards.all'));

        $response->assertJson(
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
        )->assertOk();
    }

    #[Test]
    public function user_cannot_get_boards_without_authorization(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->getJson(route('api.boards.all'));

        $response->assertForbidden();
    }

    #[Test]
    public function user_can_get_single_board_by_id(): void
    {
        $this->seed(PermissionSeeder::class);
        $board = Board::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('list-boards'))
            ->getJson(route('api.boards.getById', [$board]));

        $response->assertJson(
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
        )->assertOk();
    }

    #[Test]
    public function user_gets_404_error_when_he_wants_to_get_a_board_that_does_not_exist(): void
    {
        $this->seed(PermissionSeeder::class);

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('list-boards'))
            ->getJson(route('api.boards.getById', ['board' => 99]));

        $response->assertNotFound();
    }

    #[Test]
    public function user_cannot_get_single_board_by_id_without_permission(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->getJson(route('api.boards.getById', [Board::factory()->create()]));

        $response->assertForbidden();
    }

    #[Test]
    public function user_can_delete_board_by_id(): void
    {
        $this->seed(PermissionSeeder::class);
        $board = Board::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('delete-boards'))
            ->deleteJson(route('api.boards.deleteById', [$board]));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('boards', ['id' => $board->id]);
    }

    #[Test]
    public function user_cannot_delete_board_by_id_without_permissions(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->deleteJson(route('api.boards.deleteById', [Board::factory()->create()]));
        $response->assertForbidden();
    }

    #[Test]
    public function user_gets_404_error_when_he_wants_to_delete_a_board_that_does_not_exist(): void
    {
        $this->seed(PermissionSeeder::class);

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('delete-boards'))
            ->deleteJson(route('api.boards.deleteById', ['board' => 99]));

        $response->assertNotFound();
    }

    #[Test]
    public function user_can_update_boards(): void
    {
        $this->seed(PermissionSeeder::class);
        $params = $this->getParams();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('edit-boards'))
            ->patchJson(route('api.boards.updateById', [Board::factory()->create()]), $params->toArray());

        $response->assertJson(
            fn (AssertableJson $json) => $json->whereAll($params->toArray())
                ->etc()
        )->assertOk();
    }

    private function getParams(): Collection
    {
        return Collection::make([
            'name' => $this->faker->name,
            'hex_color' => $this->faker->hexColor(),
        ]);
    }

    #[Test]
    public function user_cannot_update_boards_without_permissions(): void
    {
        $params = $this->getParams();

        $response = $this
            ->actingAs(User::factory()->create())
            ->patchJson(route('api.boards.updateById', [Board::factory()->create()]), $params->toArray());

        $response->assertForbidden();
    }

    #[Test]
    public function user_gets_404_error_when_he_wants_update_a_board_that_does_not_exist(): void
    {
        $this->seed(PermissionSeeder::class);

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('edit-boards'))
            ->patchJson(route('api.boards.updateById', ['board' => 99]));

        $response->assertNotFound();
    }

    #[Test]
    public function user_can_create_boards(): void
    {
        $this->seed(PermissionSeeder::class);
        $params = $this->getParams();

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('create-boards'))
            ->postJson(route('api.boards.create'), $params->toArray());

        $response->assertJson(
            fn (AssertableJson $json) => $json->whereAll($params->toArray())
                ->etc()
        )->assertCreated();
    }

    #[Test]
    public function user_cannot_create_boards_with_incorrect_data(): void
    {
        $this->seed(PermissionSeeder::class);

        $response = $this
            ->actingAs(User::factory()->create()->givePermissionTo('create-boards'))
            ->postJson(route('api.boards.create', []));

        $response->assertJson(
            fn (AssertableJson $json) => $json->has('message')
                ->has('errors')
                ->whereType('errors', 'array')
                ->whereType('message', 'string')
                ->has('errors', fn (AssertableJson $json) => $json->hasAll(['name', 'hex_color']))
                ->has('message')
                ->etc()
        )->assertUnprocessable();
    }

    #[Test]
    #[DataProvider('formatsForBoardNotification')]
    public function send_board_as_xls_and_pdf_by_mail_notification(string $format): void
    {
        Notification::fake();

        $this->seed(PermissionSeeder::class);
        $board = Board::factory()->create();
        $user = User::factory()->create()->givePermissionTo('download-boards');

        $this
            ->actingAs($user)
            ->getJson(route('api.boards.download', ['format' => $format, $board]))
            ->assertAccepted();

        Notification::assertSentToTimes($user, DownloadedBoard::class);

    }

    #[Test]
    public function send_board_by_mail_is_validated(): void
    {
        Notification::fake();

        $this->seed(PermissionSeeder::class);
        $board = Board::factory()->create();
        $user = User::factory()->create()->givePermissionTo('download-boards');

        $this
            ->actingAs($user)
            ->getJson(route('api.boards.download', [$board]))
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('message')
                ->has('errors', fn (AssertableJson $json) => $json->has('format'))
            )->assertUnprocessable();

        Notification::assertNothingSent();
    }

    public static function formatsForBoardNotification(): array
    {
        return [
            ['format' => 'xls'],
            ['format' => 'pdf'],
        ];
    }
}
