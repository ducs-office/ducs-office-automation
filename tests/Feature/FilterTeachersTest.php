<?php

namespace Tests\Feature;

use App\College;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Teacher;
use App\Course;
use App\Programme;
use App\PastTeachersProfile;

class FilterTeachersTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function teachers_can_be_filtered_based_on_course_taught()
    {
        $this->signIn();

        $teachers = create(Teacher::class, 3);
        $programmes = create(Programme::class, 2);
        $courses = create(Course::class, 2);

        foreach ($programmes as $index => $programme) {
            $revision = $programme->revisions()->create(['revised_at' => $programme->wef]);
            $courses[$index]->programme_revisions()->attach($revision, ['semester' => 1]);
        }
        
        for ($index = 0; $index < 2; $index++) {             // add a past profile to each teacher
            $teachers[$index]->past_profiles()->create([
                'valid_from' => now()->subYear($index + 1),
                'college_id' => create(College::class)->id,
                'designation' => $this->faker->randomElement(array_keys(config('options.teachers.designations'))),
            ]);

            $teachers[$index]->past_profiles[0]
                ->past_teaching_details()
                ->attach([ $index + 1 ]);
        }

        $teachers[2]->past_profiles()->create([
            'valid_from' => now()->subYear(3),
            'college_id' => create(College::class)->id,
            'designation' => $this->faker->randomElement(array_keys(config('options.teachers.designations'))),
        ]);

        $teachers[2]->past_profiles[0]->past_teaching_details()->attach([1]);

        $viewTeachers = $this->withoutExceptionHandling()
                        ->get(route('staff.teachers.index', [
                            'filters' => ['course_id' => [ $courses[0]->id ] ],
                        ]))->assertSuccessful()
                            ->assertViewIs('staff.teachers.index')
                            ->assertViewHas('Teachers')
                            ->viewData('Teachers');
                            
        $this->assertEquals(2, $viewTeachers->count());
        $this->assertSame($viewTeachers->pluck('id')->toArray(), [1,3]);
    }
    
    /** @test */
    public function teachers_who_taught_after_given_date_can_be_filterd()
    {
        $this->signIn();

        $fileterd_teachers = create(Teacher::class, 2);
        $non_fileterd_teachers = create(Teacher::class, 2);

        $x = '2015-01-01';

        $fileterd_teachers[0]->past_profiles()->create([
            'college_id' => create(College::class)->id,
            'designation' => 'A',
            'valid_from' => '2015-01-01',
        ]);

        $fileterd_teachers[1]->past_profiles()->createMany([
            [
                'college_id' => create(College::class)->id,
                'designation' => 'A',
                'valid_from' => '2017-09-09',
            ],
            [
                'college_id' => create(College::class)->id,
                'designation' => 'A',
                'valid_from' => '2010-09-09',
            ]
        ]);

        $non_fileterd_teachers[0]->past_profiles()->create([
            'college_id' => create(College::class)->id,
            'designation' => 'A',
            'valid_from' => '2012-09-08',
        ]);

        $non_fileterd_teachers[1]->past_profiles()->create([
            'college_id' => create(College::class)->id,
            'designation' => 'A',
            'valid_from' => '2014-12-31',
        ]);

        $view_teachers = $this->withoutExceptionHandling()
                ->get(route('staff.teachers.index', [
                    'filters' => ['valid_from' => ['greater_than' => $x]]
                ]))
                ->assertSuccessful()
                ->assertViewIs('staff.teachers.index')
                ->assertViewHas('Teachers')
                ->viewData('Teachers');

        $this->assertEquals(count($fileterd_teachers), count($view_teachers));
        $this->assertTrue($view_teachers->pluck('id')->contains($fileterd_teachers[0]->id));
        $this->assertTrue($view_teachers->pluck('id')->contains($fileterd_teachers[1]->id));
    }
}
