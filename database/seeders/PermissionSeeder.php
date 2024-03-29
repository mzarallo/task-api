<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'create-users', 'category' => 'Users'],
            ['name' => 'edit-users', 'category' => 'Users'],
            ['name' => 'list-users', 'category' => 'Users'],
            ['name' => 'delete-users', 'category' => 'Users'],
            ['name' => 'create-boards', 'category' => 'Boards'],
            ['name' => 'create-stages', 'category' => 'Stages'],
            ['name' => 'create-tasks', 'category' => 'Tasks'],
            ['name' => 'create-comments', 'category' => 'Comments'],
            ['name' => 'edit-boards', 'category' => 'Boards'],
            ['name' => 'edit-stages', 'category' => 'Stages'],
            ['name' => 'edit-tasks', 'category' => 'Tasks'],
            ['name' => 'edit-comments', 'category' => 'Comments'],
            ['name' => 'delete-boards', 'category' => 'Boards'],
            ['name' => 'delete-stages', 'category' => 'Stages'],
            ['name' => 'delete-tasks', 'category' => 'Tasks'],
            ['name' => 'delete-comments', 'category' => 'Comments'],
            ['name' => 'list-boards', 'category' => 'Boards'],
            ['name' => 'list-stages', 'category' => 'Stages'],
            ['name' => 'list-tasks', 'category' => 'Tasks'],
            ['name' => 'list-comments', 'category' => 'Comments'],
            ['name' => 'list-permissions', 'category' => 'Permissions'],
            ['name' => 'list-roles', 'category' => 'Roles'],
            ['name' => 'download-boards', 'category' => 'Boards'],
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission['name'], 'category' => $permission['category']]);
        }
    }
}
