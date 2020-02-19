<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\PastTeachersProfile;

class AcceptingTeachingRecordsStarted extends Notification
{
    // use Queueable;

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
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('We\'ve started accepting teaching records from '. $this->start_date . '. Please visit your profile')
                    ->line('The deadline to make submissionsis ' . $this->end_date)
                    ->action('Profile', url(route('teachers.profile')))
                    ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
      */
    // public function toArray($notifiable)
    // {
    //     return [
    //         //
    //     ];
    // }

    public function toDatabase()
    {
        return [
            'inform' => 'Accepting details has begun from '. $this->start_date . '. The deadline to submit profiles is '
                    . $this->end_date
        ];
    }
}
