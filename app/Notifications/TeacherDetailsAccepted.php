<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Teacher;

class TeacherDetailsAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('teachers.profile');
        return (new MailMessage)
                    ->line('Your teaching details has been submitted.')
                    ->action('View Teaching Details', url($url))
                    ->line('Thank you!');
    }
}
