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
    'phd_courses' => [
        'types' => [
            'C' => 'Core',
            'E' => 'Elective',
        ],
    ],
    'teachers' => [
        'designations' => [
            'P' => 'Permanent',
            'A' => 'Ad-Hoc',
            'T' => 'Temporary',
        ],
    ],
    'scholars' => [
        'categories' => [
            'G' => 'General',
            'O' => 'OBC',
            'SC' => 'SC',
            'ST' => 'ST',
        ],
        'admission_criterias' => [
            'NET' => ['mode' => 'UGC NET', 'funding' => 'Employed'],
            'T' => ['mode' => 'DU College Teacher', 'funding' => 'Employed'],
            'M' => ['mode' => 'MOU', 'funding' => 'Employed'],
            'F' => ['mode' => 'Foreign Candidate', 'funding' => 'Employed'],
            'E' => ['mode' => 'Enterance', 'funding' => 'Non-NET'],
            'J' => ['mode' => 'JRF', 'funding' => 'JRF'],
            'D' => ['mode' => 'DRDO', 'funding' => 'Employed'],
        ],
        'genders' => [
            'F' => 'Female',
            'M' => 'Male',
            'T' => 'Transgender',
            'N' => 'Do not wish to declare',
        ],
        'academic_details' => [
            'indexed_in' => [
                'Scopus' => 'Scopus',
                'SCI' => 'SCI',
                'SCIE' => 'SCIE',
            ],
        ],
    ],
];
