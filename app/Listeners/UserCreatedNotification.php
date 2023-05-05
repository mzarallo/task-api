<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Actions\User\Notifications\Mails\SendCredentialsEmailToUser;
use App\Events\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreatedNotification implements ShouldQueue
{
    public $queue = 'listeners';

    public function __construct(private SendCredentialsEmailToUser $emailWithCredentialToUser)
    {
    }

    public function handle(UserCreated $event): void
    {
        $this->emailWithCredentialToUser->run($event->user, $event->password);
    }
}
