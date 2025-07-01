<?php

declare(strict_types=1);

use App\Actions\Users\Notifications\Mails\SendCredentialsEmailToUser;
use App\Mail\CredentialsUserMail;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;

uses(WithFaker::class);

it('send credentials to user', function () {
    Mail::fake();
    $user = User::factory()->create();

    SendCredentialsEmailToUser::make()->handle($user, $this->faker->word());

    Mail::assertSent(CredentialsUserMail::class, 1);
});
