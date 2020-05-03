<?php

use App\Models\ScholarEducationDegree;
use App\Models\ScholarEducationInstitute;
use App\Models\ScholarEducationSubject;
use Illuminate\Database\Seeder;

class ScholarEducationSeeder extends Seeder
{
    public function run()
    {
        factory(ScholarEducationDegree::class)->create([
            'name' => 'BSc Programme',
        ]);

        factory(ScholarEducationSubject::class)->create([
            'name' => 'Mathematics',
        ]);

        factory(ScholarEducationSubject::class)->create([
            'name' => 'Mathematical Science',
        ]);

        factory(ScholarEducationInstitute::class)->create([
            'name' => 'IP College',
        ]);
    }
}
