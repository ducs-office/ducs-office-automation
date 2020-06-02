<?php

use App\Models\College;
use App\Models\Course;
use App\Models\CourseRevision;
use App\Models\Handover;
use App\Models\IncomingLetter;
use App\Models\LetterReminder;
use App\Models\OutgoingLetter;
use App\Models\Programme;
use App\Models\ProgrammeRevision;
use App\Models\Publication;
use App\Models\Remark;
use App\Models\Scholar;
use App\Models\ScholarEducationDegree;
use App\Models\ScholarEducationInstitute;
use App\Models\ScholarEducationSubject;
use App\Models\ScholarProfile;
use App\Models\User;
use App\Types\CourseType;
use App\Types\Designation;
use App\Types\ProgrammeType;
use App\Types\UserCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = factory(User::class)->create([
            'name' => 'Administrator',
            'email' => 'admin@cs.du.ac.in',
            'password' => bcrypt('password'),
            'category' => UserCategory::OFFICE_STAFF,
        ]);

        $admin->assignRole(Role::firstOrCreate(['name' => 'admin']));

        $outgoing = factory(OutgoingLetter::class, 5)->create(['creator_id' => $admin->id]);

        $outgoing->each(static function ($letter) {
            factory(IncomingLetter::class)->create([
                'recipient_id' => $letter->sender_id,
                'creator_id' => $letter->creator_id,
            ]);
        });

        $programmes = collect([
            factory(Programme::class)->create(['code' => 'BSCH', 'name' => 'B.Sc. (H) Computer Science', 'duration' => '3', 'type' => ProgrammeType::UNDER_GRADUATE]),
            factory(Programme::class)->create(['code' => 'BSCP', 'name' => 'B.Sc. (Prog) Computer Science', 'duration' => '3', 'type' => ProgrammeType::UNDER_GRADUATE]),
            factory(Programme::class)->create(['code' => 'MCA', 'name' => 'Masters in Computer Application', 'name' => 'MCA', 'duration' => '3', 'type' => ProgrammeType::POST_GRADUATE]),
            factory(Programme::class)->create(['code' => 'MCS', 'name' => 'M.Sc. Computer Science', 'duration' => '3', 'type' => ProgrammeType::POST_GRADUATE]),
        ]);

        $programme_revisions = $programmes->map(static function ($programme) {
            return factory(ProgrammeRevision::class)->create([
                'revised_at' => $programme->wef,
                'programme_id' => $programme->id,
            ]);
        });

        $courses = collect([
            factory(Course::class)->create(['code' => 'MCS101', 'name' => 'Design and Analysis of Algorithms', 'type' => CourseType::CORE]),
            factory(Course::class)->create(['code' => 'MCS102', 'name' => 'Artificial Intelligence']),
            factory(Course::class)->create(['code' => 'MCS203', 'name' => 'Compiler Design']),
            factory(Course::class)->create(['code' => 'MCS204', 'name' => 'Data Mining']),
            factory(Course::class)->create(['code' => 'MCS301', 'name' => 'Machine Learning']),
            factory(Course::class)->create(['code' => 'MCA101', 'name' => 'System Programming']),
            factory(Course::class)->create(['code' => 'MCA201', 'name' => 'Computer Graphics']),
            factory(Course::class)->create(['code' => 'BSCPC101', 'name' => 'Programming Fundamentals in C++']),
            factory(Course::class)->create(['code' => 'BSCHC101', 'name' => 'Computer System Architecture']),
            factory(Course::class)->create(['code' => 'BSCHC201', 'name' => 'Java Programming']),
            factory(Course::class)->create(['code' => 'BSCHC202', 'name' => 'Data Structures']),
            factory(Course::class)->create(['code' => 'BSCHC301', 'name' => 'Internet Technology']),
            factory(Course::class)->create(['code' => 'BSCHSEC301', 'name' => 'PHP Programming']),
            factory(Course::class)->create(['code' => 'BSCHSEC401', 'name' => 'Android Programming']),
        ]);

        $programme_revisions->each(function ($revision) use ($courses) {
            return $courses->filter(function ($course) use ($revision) {
                return Str::startsWith($course->code, $revision->programme->code);
            })->each(function ($course) use ($revision) {
                $matches = [];
                preg_match('/([1-9])[0-9]*$/', $course->code, $matches);
                return $revision->courses()->attach($course, [
                    'semester' => $matches[1],
                ]);
            });
        });

        $courses->each(static function ($course) {
            factory(CourseRevision::class, 3)->create(['course_id' => $course->id]);
        });
        $programmes->each(static function ($programme) {
            factory(ProgrammeRevision::class)->create(['programme_id' => $programme->id]);
        });

        $andc = factory(College::class)->create([
            'code' => 'DU-ANDC',
            'name' => 'Acharya Narendra Dev College',
        ]);
        $andc->programmes()->sync($programmes->pluck('id')->toArray());

        factory(College::class)->create([
            'code' => 'DU-KMV',
            'name' => 'Keshav Mahavidyalaya',
        ])->programmes()->sync($programmes->pluck('id')->toArray());

        factory(College::class)->create([
            'code' => 'DU-HRC',
            'name' => 'Hansraj College, University of Delhi',
        ])->programmes()->sync($programmes->pluck('id')->toArray());

        $this->call(PhdCourseSeeder::class);

        $this->call(PhdScholarSeeder::class);

        $this->call(ScholarEducationSeeder::class);
    }
}
