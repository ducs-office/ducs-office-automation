<?php

namespace App\Listeners;

use App\Events\ProgrammeCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddCoursesToProgramme
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
     * @param  ProgrammeCreated  $event
     *
     * @return void
     */
    public function handle(ProgrammeCreated $event)
    {
        $programmeRevision = $event->programme->revisions()->create(['revised_at' => $event->programme->wef]);

        foreach ($event->semester_courses as $index => $courses) {
            $programmeRevision->courses()->attach($courses, ['semester' => $index + 1]);
        }
    }
}
