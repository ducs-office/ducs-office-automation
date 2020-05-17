<?php

namespace App\Listeners;

use App\Events\ScholarCreated;
use App\Events\UserCreated;
use App\Notifications\UserRegisteredNotification;
use Illuminate\Support\Facades\Password;

class SendUserRegisteredNotification
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     *
     * @return void
     */
    public function handle($event)
    {
        if ($event instanceof ScholarCreated) {
            return $this->handleScholarEvent($event);
        }

        return $this->handleUserEvent($event);
    }

    private function handleScholarEvent(ScholarCreated $event)
    {
        $token = Password::broker('scholars')
            ->createToken($event->scholar);

        $event->scholar->notify(
            new UserRegisteredNotification($token)
        );
    }

    private function handleUserEvent(UserCreated $event)
    {
        $token = Password::broker('users')
            ->createToken($event->user);

        $event->user->notify(
            new UserRegisteredNotification($token)
        );
    }
}
