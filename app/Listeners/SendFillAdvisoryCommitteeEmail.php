<?php

namespace App\Listeners;

use App\Events\ScholarCreated;
use App\Mail\FillAdvisoryCommitteeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendFillAdvisoryCommitteeEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     *
     * @return void
     */
    public function handle(ScholarCreated $event)
    {
        Mail::to($event->scholar->supervisor)
            ->send(new FillAdvisoryCommitteeMail($event->scholar));
    }
}
