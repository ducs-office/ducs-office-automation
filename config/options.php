<?php

return [
    'incoming_letters' => [
        'priorities' => [
            '1' => 'High',
            '2' => 'Medium',
            '3' => 'Low',
        ],
        'priority_colors' => [
            '1' => 'text-red-600',
            '2' => 'text-blue-600',
            '3' => 'text-yellow-800',
        ],
    ],
    'users' => [
        'categories' => [
            'admin' => 'Admin',
            'hod' => 'HOD',
            'office_staff' => 'Office Staff',
            'faculty_teacher' => 'Faculty Teacher',
            'teacher' => 'College Teacher',
        ],
    ],
    'programmes' => [
        'types' => [
            'UG' => 'Under Graduate',
            'PG' => 'Post Graduate',
        ],
    ],
    'courses' => [
        'types' => [
            'C' => 'Core',
            'GE' => 'General Elective',
            'OE' => 'Open Elective',
        ],
    ],
    'teachers' => [
        'designations' => [
            'P' => 'Permanent',
            'G' => 'Guest',
            'A' => 'Ad-Hoc',
            'T' => 'Temporary',
        ],
    ],
];
