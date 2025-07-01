<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Users;

use App\Actions\Tasks\GetAllTasks;
use App\Actions\Users\GetAllUsers;
use App\Data\Services\Tasks\GetAllTaskServiceDto;
use App\Data\Services\Users\GetAllUsersServiceDto;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GetAllUsersTest extends TestCase
{
    #[Test]
    public function it_get_users_paginated(): void
    {
        User::factory()->count(2)->create();

        $response = GetAllUsers::make()->handle(
            GetAllUsersServiceDto::from([
                'paginated' => true,
            ])
        );

        $this->assertCount(2, $response->items());
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertContainsOnlyInstancesOf(User::class, $response->items());
    }

    /**
     * @test
     */
    public function it_get_users_as_collection(): void
    {
        User::factory()->count(2)->create();

        $response = GetAllUsers::make()->handle(
            GetAllUsersServiceDto::from([
                'paginated' => false,
            ])
        );

        $this->assertCount(2, $response);
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertContainsOnlyInstancesOf(User::class, $response);
    }

    /**
     * @test
     */
    public function it_get_tasks_with_relations_loaded(): void
    {
        Task::factory()->count(2)->create();
        Notification::fake();

        $response = GetAllTasks::make()->handle(
            GetAllTaskServiceDto::from([
                'relations' => ['author', 'stage'],
                'paginated' => true,
            ])
        );

        $this->assertTrue($response->offsetGet(0)->relationLoaded('author'));
        $this->assertTrue($response->offsetGet(0)->relationLoaded('stage'));
    }
}
