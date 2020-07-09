<?php

namespace App\Types;

class UserCategory extends BaseEnumType
{
    const OFFICE_STAFF = 'Office Staff';
    const LAB_STAFF = 'Lab Staff';
    const FACULTY_TEACHER = 'Department Teacher';
    const COLLEGE_TEACHER = 'College Teacher';
    const EXTERNAL = 'External';
}
