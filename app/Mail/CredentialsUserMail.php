<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredentialsUserMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(protected User $user, protected string $password) {}

    public function build(): self
    {
        return $this
            ->to($this->user->email)
            ->subject('Bienvenido a TaskApp')
            ->view('emails.userCredentialsMail')
            ->with([
                'user' => $this->user,
                'password' => $this->password,
            ]);
    }
}
