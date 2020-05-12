<?php

namespace App\Mail;

use App\Models\Scholar;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FillAdvisoryCommitteeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $supervisor;
    protected $scholarName;
    protected $deadline;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Scholar $scholar)
    {
        $this->supervisor = $scholar->currentSupervisor;
        $this->scholarName = $scholar->name;
        $this->deadline = $scholar->created_at->addDays(15)->format('d F Y');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.supervisors.fill_advisory_committee', [
            'supervisor' => $this->supervisor,
            'scholarName' => $this->scholarName,
            'deadline' => $this->deadline,
        ]);
    }
}
