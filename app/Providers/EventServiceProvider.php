<?php

namespace App\Providers;

use App\Events\ProgrammeCreated;
use App\Events\ScholarCreated;
use App\Events\UserCreated;
use App\Listeners\AddCoursesToProgramme;
use App\Listeners\SendFillAdvisoryCommitteeEmail;
use App\Listeners\SendRegisteredEmail;
use App\Listeners\SendUserRegisteredNotification;
use App\Listeners\SendWelcomeNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ProgrammeCreated::class => [
            AddCoursesToProgramme::class,
        ],
        UserCreated::class => [
            SendUserRegisteredNotification::class,
        ],
        ScholarCreated::class => [
            SendUserRegisteredNotification::class,
            SendFillAdvisoryCommitteeEmail::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Event::listen('ProgrammeCreated', 'AddCoursesToProgramme');
    }
}
