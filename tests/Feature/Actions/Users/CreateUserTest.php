<?php

declare(strict_types=1);

use App\Actions\Users\CreateUser;
use App\Data\Services\Users\CreateUserServiceDto;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

uses(WithFaker::class);

it('create a user', function () {
    Mail::fake();
    Role::query()->create(['name' => 'Admin']);

    $response = CreateUser::make()->handle(
        CreateUserServiceDto::from([
            'name' => $this->faker->sentence,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'role' => 'Admin',
        ])
    );

    expect($response)->toBeInstanceOf(User::class);
});
