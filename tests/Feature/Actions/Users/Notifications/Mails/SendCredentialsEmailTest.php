<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Users\Notifications\Mails;

use App\Actions\Users\Notifications\Mails\SendCredentialsEmailToUser;
use App\Mail\CredentialsUserMail;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SendCredentialsEmailTest extends TestCase
{
    use WithFaker;

    #[Test]
    public function it_send_credentials_to_user(): void
    {
        Mail::fake();
        $user = User::factory()->create();

        SendCredentialsEmailToUser::make()->handle($user, $this->faker->word());

        Mail::assertSent(CredentialsUserMail::class, 1);
    }
}
