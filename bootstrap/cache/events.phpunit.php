<?php return array (
  'App\\Providers\\EventServiceProvider' => 
  array (
    'App\\Events\\ProgrammeCreated' => 
    array (
      0 => 'App\\Listeners\\AddCoursesToProgramme',
    ),
    'App\\Events\\UserCreated' => 
    array (
      0 => 'App\\Listeners\\SendUserRegisteredNotification',
    ),
    'App\\Events\\ScholarCreated' => 
    array (
      0 => 'App\\Listeners\\SendUserRegisteredNotification',
      1 => 'App\\Listeners\\SendFillAdvisoryCommitteeEmail',
    ),
  ),
);