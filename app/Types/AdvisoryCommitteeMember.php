<?php

namespace App\Types;

use App\Models\SupervisorProfile;
use App\Models\User;

class AdvisoryCommitteeMember
{
    public $type;
    public $id;
    public $name;
    public $designation;
    public $affiliation;
    public $email;
    public $phone;

    public function __construct($type, $attributes)
    {
        $this->type = $type;
        extract($attributes);
        $this->id = $id ?? null;
        $this->name = $name;
        $this->designation = $designation;
        $this->affiliation = $affiliation;
        $this->email = $email;
        $this->phone = $phone ?? null;
    }

    public static function fromFacultyTeacher(User $facultyTeacher)
    {
        return new self('faculty_teacher', [
            'id' => $facultyTeacher->id,
            'name' => $facultyTeacher->name,
            'designation' => 'Professor',
            'affiliation' => 'Department of Computer Science',
            'email' => $facultyTeacher->email,
        ]);
    }

    public static function fromExistingSupervisors(SupervisorProfile $supervisorProfile)
    {
        $affiliation = $supervisorProfile->supervisor instanceof User ?
            'Department of Computer Science' :
            $supervisorProfile->supervisor->profile->college->name ??
            'Affiliation Not Set';

        return new self('existing_supervisor', [
            'id' => $supervisorProfile->id,
            'name' => $supervisorProfile->supervisor->name,
            'designation' => 'Professor',
            'affiliation' => $affiliation,
            'email' => $supervisorProfile->supervisor->email,
        ]);
    }

    public function toArray()
    {
        return [
            'type' => $this->type,
            'id' => $this->id,
            'name' => $this->name,
            'designation' => $this->designation,
            'affiliation' => $this->affiliation,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }
}
