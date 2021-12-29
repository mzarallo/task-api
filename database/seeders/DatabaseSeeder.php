<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\Comment;
use App\Models\Stage;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();
        Board::factory(3)->create();
        Stage::factory(15)->create();
        Task::factory(100)->create();
        Comment::factory(300)->create();
    }
}
