<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
        ]);

        $this->call([
            PermissionSeeder::class,
        ]);

        Comment::factory(10)->create();
    }
}
