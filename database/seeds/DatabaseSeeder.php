<?php

use App\Programme;
use App\ProgrammeRevision;
use App\OutgoingLetter;
use App\Course;
use App\User;
use App\College;
use App\Teacher;
use App\CourseRevision;
use App\IncomingLetter;
use App\LetterReminder;
use App\Remark;
use App\Handover;
use App\TeacherProfile;
use Illuminate\Database\Seeder;
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
            'category' => 'Admin'
        ]);


        $admin->assignRole(Role::firstOrCreate(['name' => 'admin']));

        $outgoing = factory(OutgoingLetter::class, 5)->create(['creator_id' => $admin->id]);

        $outgoing->each(function ($letter) {
            factory(IncomingLetter::class)->create([
                'recipient_id' => $letter->sender_id,
                'creator_id' => $letter->creator_id
            ]);
        });

        $programmes = collect([
            factory(Programme::class)->create(['code' => 'BSCH', 'name' => 'B.Sc. (H) Computer Science', 'duration' => '3', 'type' => 'UG']),
            factory(Programme::class)->create(['code' => 'BSCP', 'name' => 'B.Sc. (Prog) Computer Science', 'duration' => '3', 'type' => 'UG']),
            factory(Programme::class)->create(['code' => 'MCA', 'name' => 'Masters in Computer Application', 'name' => 'MCA', 'duration' => '3', 'type' => 'PG']),
            factory(Programme::class)->create(['code' => 'MCS', 'name' => 'M.Sc. Computer Science', 'duration' => '3', 'type' => 'PG']),
        ]);

        $programme_revisions = $programmes->map(function ($programme) {
            return factory(ProgrammeRevision::class)->create([
                'revised_at' => $programme->wef,
                'programme_id' => $programme->id
            ]);
        });

        $courses = collect([
            factory(Course::class)->create(['code' => 'MCS101', 'name' => 'Design and Analysis of Algorithms', 'type' => 'C']),
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
                return starts_with($course->code, $revision->programme->code);
            })->each(function ($course) use ($revision) {
                $matches = [];
                preg_match('/([1-9])[0-9]*$/', $course->code, $matches);
                return $revision->courses()->attach($course, [
                    'semester' => $matches[1],
                ]);
            });
        });

        $courses->each(function ($course) {
            factory(CourseRevision::class, 3)->create(['course_id' => $course->id]);
        });
        $programmes->each(function ($programme) {
            factory(ProgrammeRevision::class)->create(['programme_id' => $programme->id]);
        });

        $andc = factory(College::class)->create([
            'code' => 'DU-ANDC-001',
            'name' => 'Acharya Narendra Dev College'
        ]);
        $andc->programmes()->sync($programmes->pluck('id')->toArray());

        factory(College::class)->create([
            'code'=> 'DU-KMV-002' ,
            'name' => 'Keshav Mahavidyalaya'
        ])->programmes()->sync($programmes->pluck('id')->toArray());

        factory(College::class)->create([
            'code'=> 'DU-HRC-003' ,
            'name' => 'Hansraj College'
        ])->programmes()->sync($programmes->pluck('id')->toArray());

        $teacher = factory(Teacher::class)->create([
            'first_name' => 'Sharanjit',
            'last_name' => 'Kaur',
            'email' => 'kaur.sharanjit@andc.du.ac.in',
        ]);
    }
}
