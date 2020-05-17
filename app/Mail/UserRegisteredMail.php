<?php

namespace App\Mail;

use App\Models\Scholar;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegisteredMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $user;
    protected $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->user instanceof Scholar) {
            return $this->buildScholarMail();
        }

        return $this->markdown('mail.users.registered', [
            'user' => $this->user,
            'token' => $this->token,
        ]);
    }

    private function buildScholarMail()
    {
        return $this->markdown('mail.scholars.registered', [
            'scholar' => $this->user,
            'supervisor' => $this->user->currentSupervisor,
            'cosupervisor' => $this->user->currentCosupervisor,
            'token' => $this->token,
        ]);
    }
}
