<?php

use App\Course;
use App\OutgoingLetter;
use App\Paper;
use App\User;
use App\College;
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
        $himani = factory(User::class)->create([
            'name' => 'Himani Saini',
            'email' => 'himani@ducs.in',
            'password' => bcrypt('secret'),
        ]);

        $ng = factory(User::class)->create([
            'name' => 'Neelima Gupta',
            'email' => 'ng@ducs.in',
            'password' => bcrypt('secret'),
        ]);

        $nk = factory(User::class)->create([
            'name' => 'Naveen Kumar',
            'email' => 'nk@ducs.in',
            'password' => bcrypt('secret'),
        ]);

        $himani->assignRole(Role::firstOrCreate(['name' => 'admin_Staff']));

        factory(OutgoingLetter::class, 5)->create(['sender_id' => $ng->id, 'creator_id' => $himani->id]);
        factory(OutgoingLetter::class, 5)->create(['sender_id' => $nk->id, 'creator_id' => $himani->id]);

        factory(Course::class, 5)->create()->each(function($course) {
            factory(Paper::class, 10)->create(['course_id' => $course->id]);
        });

        factory(College::class)->create([
            'code'=> 'DU-KMV-001' ,
            'name' => 'Keshav Mahavidyalaya'
        ]);

        factory(College::class)->create([
            'code' => 'DU-ARSD-002',
            'name' => 'Atma Ram Sanatan Dharma College'
        ]);

        factory(College::class,5)->create();

    }
}
