<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Tasks;

use App\Actions\Tasks\CreateTask;
use App\Data\Services\Tasks\CreateTaskServiceDto;
use App\Models\Stage;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    use WithFaker;

    #[Test]
    public function it_create_a_task(): void
    {
        $response = CreateTask::make()->handle(
            CreateTaskServiceDto::from([
                'title' => $this->faker->sentence,
                'description' => $this->faker->realText,
                'author_id' => User::factory()->create()->id,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addWeek()->toDateString(),
                'tags' => ['QA', 'DEV'],
                'order' => 1,
                'stage_id' => Stage::factory()->create()->id,
            ])
        );

        $this->assertInstanceOf(Task::class, $response);
    }
}
