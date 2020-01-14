<?php

use App\Programme;
use App\OutgoingLetter;
use App\Course;
use App\User;
use App\College;
use App\IncomingLetter;
use App\LetterReminder;
use App\Remark;
use App\Handover;
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
            factory(Programme::class)->create(['name' => 'B.Sc. (H) Computer Science', 'type' => 'UG']),
            factory(Programme::class)->create(['name' => 'B.Sc. (Prog) Computer Science', 'type' => 'UG']),
            factory(Programme::class)->create(['name' => 'MCA', 'type' => 'PG']),
        ]);

        factory(Course::class)->create(['name' => 'Design and Analysis of Algorithms', 'type' => 'C']);
        factory(Course::class)->create(['name' => 'Artificial Intelligence']);
        factory(Course::class)->create(['name' => 'Compiler Design']);
        factory(Course::class)->create(['name' => 'Data Mining']);
        factory(Course::class)->create(['name' => 'Machine Learning']);
        factory(Course::class)->create(['name' => 'Internet Technology']);
        factory(Course::class)->create(['name' => 'Android Programming']);
        factory(Course::class)->create(['name' => 'PHP Programming']);
        factory(Course::class)->create(['name' => 'Data Structures']);

        factory(College::class)->create([
            'code' => 'DU-ANDC-001',
            'name' => 'Acharya Narendra Dev College'
        ])->programmes()->sync($programmes->pluck('id')->toArray());

        factory(College::class)->create([
            'code'=> 'DU-KMV-002' ,
            'name' => 'Keshav Mahavidyalaya'
        ])->programmes()->sync($programmes->pluck('id')->toArray());

        factory(College::class)->create([
            'code'=> 'DU-HRC-003' ,
            'name' => 'Hansraj College'
        ])->programmes()->sync($programmes->pluck('id')->toArray());
    }
}
