<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'can-create-users', 'category' => 'Users'],
            ['name' => 'can-edit-users', 'category' => 'Users'],
            ['name' => 'can-list-users', 'category' => 'Users'],
            ['name' => 'can-delete-users', 'category' => 'Users'],
            ['name' => 'can-create-boards', 'category' => 'Boards'],
            ['name' => 'can-create-stages', 'category' => 'Stages'],
            ['name' => 'can-create-tasks', 'category' => 'Tasks'],
            ['name' => 'can-create-comments', 'category' => 'Comments'],
            ['name' => 'can-edit-boards', 'category' => 'Boards'],
            ['name' => 'can-edit-stages', 'category' => 'Stages'],
            ['name' => 'can-edit-tasks', 'category' => 'Tasks'],
            ['name' => 'can-edit-comments', 'category' => 'Comments'],
            ['name' => 'can-delete-boards', 'category' => 'Boards'],
            ['name' => 'can-delete-stages', 'category' => 'Stages'],
            ['name' => 'can-delete-tasks', 'category' => 'Tasks'],
            ['name' => 'can-delete-comments', 'category' => 'Comments'],
            ['name' => 'can-list-boards', 'category' => 'Boards'],
            ['name' => 'can-list-stages', 'category' => 'Stages'],
            ['name' => 'can-list-tasks', 'category' => 'Tasks'],
            ['name' => 'can-list-comments', 'category' => 'Comments'],
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission['name'], 'category' => $permission['category']]);
        }

        User::all()->each( fn ($user) => $user->givePermissionTo(Permission::all()->pluck('name')->toArray()));
    }
}
