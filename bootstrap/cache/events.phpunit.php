<?php return [
    'App\\Providers\\EventServiceProvider' => [
        'App\\Events\\ProgrammeCreated' => [
            0 => 'App\\Listeners\\AddCoursesToProgramme',
        ],
        'App\\Events\\UserCreated' => [
            0 => 'App\\Listeners\\SendUserRegisteredNotification',
        ],
        'App\\Events\\ScholarCreated' => [
            0 => 'App\\Listeners\\SendUserRegisteredNotification',
            1 => 'App\\Listeners\\SendFillAdvisoryCommitteeEmail',
        ],
    ],
];
