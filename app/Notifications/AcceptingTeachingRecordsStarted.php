<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AcceptingTeachingRecordsStarted extends Notification
{
    public $start_date;
    public $end_date;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->line(
                'We\'ve started accepting teaching records from '
                    . $this->start_date->format('d F Y')
                    . '. Please visit your profile.'
            )
            ->line('The deadline to make submissions is ' . $this->end_date->format('d F Y'))
            ->action('Profile', url(route('profiles.show', $notifiable)))
            ->line('Thank you!');
    }

    public function toDatabase()
    {
        return [
            'inform' => 'Accepting details has begun from '
                . $this->start_date->format('d F Y')
                . '. The deadline to submit profiles is '
                . $this->end_date->format('d F Y') . '.',
        ];
    }
}
