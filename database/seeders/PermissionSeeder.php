<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission['name'], 'category' => $permission['category']]);
        }

        Role::where('name', 'Administrator')->first()->givePermissionTo(Permission::all()->pluck('name')->toArray());
        Role::where('name', 'Sales Manager')->first()->givePermissionTo([
            'create-boards',
            'create-stages',
            'create-tasks',
            'create-comments',
            'edit-boards',
            'edit-stages',
            'edit-tasks',
            'edit-comments',
            'delete-boards',
            'delete-stages',
            'delete-tasks',
            'delete-comments',
            'list-boards',
            'list-stages',
            'list-tasks',
            'list-comments',
        ]);
        Role::where('name', 'Seller')->first()->givePermissionTo([
            'create-tasks',
            'create-comments',
            'edit-tasks',
            'edit-comments',
            'list-tasks',
            'list-comments',
            'delete-tasks',
            'delete-comments',
            'list-stages',
            'list-boards',
        ]);

        User::find(1)->assignRole('Administrator');

        User::where('id', '!=', 1)->get()->each( fn (User $user) => $user->assignRole(Arr::random(['Seller', 'Sales Manager'], 1)));
    }
}
