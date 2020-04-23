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
            'name' => 'BSc(H)',
        ]);

        factory(ScholarEducationDegree::class)->create([
            'name' => 'MSc',
        ]);

        factory(ScholarEducationDegree::class)->create([
            'name' => 'BSc Programme',
        ]);

        factory(ScholarEducationSubject::class)->create([
            'name' => 'Computer Science',
        ]);

        factory(ScholarEducationSubject::class)->create([
            'name' => 'Mathematics',
        ]);

        factory(ScholarEducationSubject::class)->create([
            'name' => 'Mathematical Science',
        ]);

        factory(ScholarEducationInstitute::class)->create([
            'name' => 'Shyama Prasad Mukherji College, DU',
        ]);

        factory(ScholarEducationInstitute::class)->create([
            'name' => 'University of Delhi',
        ]);

        factory(ScholarEducationInstitute::class)->create([
            'name' => 'IP College',
        ]);
    }
}
