<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeachingRecordsSaved extends Notification implements ShouldQueue
{
    use Queueable;

    public function via()
    {
        return ['database', 'mail'];
    }

    public function toMail()
    {
        $url = route('teachers.profile');
        return (new MailMessage())
            ->line('Your teaching details has been submitted.')
            ->action('View Teaching Details', url($url))
            ->line('Thank you!');
    }

    public function toDatabase()
    {
        return [
            'message' => 'Your teaching details have been recorded. Thank you!',
        ];
    }
}
