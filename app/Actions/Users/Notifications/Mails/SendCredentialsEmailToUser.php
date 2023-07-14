<?php

declare(strict_types=1);

namespace App\Actions\Users\Notifications\Mails;

use App\Mail\CredentialsUserMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Lorisleiva\Actions\Concerns\AsAction;

class SendCredentialsEmailToUser
{
    use AsAction;

    public function handle(User $user, string $password): void
    {
        Mail::send(new CredentialsUserMail($user, $password));
    }
}
