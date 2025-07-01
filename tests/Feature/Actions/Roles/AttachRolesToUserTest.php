<?php

declare(strict_types=1);

use App\Actions\Roles\AttachRoleToUser;
use App\Data\Services\Roles\AttachRoleToUserDto;
use App\Models\User;
use Database\Seeders\RoleSeeder;

use function Pest\Laravel\seed;

it('attach role to user', function () {
    seed(RoleSeeder::class);
    $user = User::factory()->create();

    $response = AttachRoleToUser::make()->handle(
        $user->id,
        AttachRoleToUserDto::from([
            'role' => 'Administrator',
        ])
    );

    expect($response)->toBeInstanceOf(User::class)
        ->and($response->hasRole('Administrator'))->toBeTrue();
});
