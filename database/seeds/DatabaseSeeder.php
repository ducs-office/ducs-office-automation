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
        ]);

        $admin->assignRole(Role::firstOrCreate(['name' => 'admin']));

        $outgoing = factory(OutgoingLetter::class, 5)->create(['creator_id' => $admin->id]);

        $outgoing->each(function ($letter) {
            factory(IncomingLetter::class)->create([
                'recipient_id' => $letter->sender_id,
                'creator_id' => $letter->creator_id
            ]);
        });

        factory(Programme::class, 3)->create()->each(function ($programme) {
            factory(Course::class, 10)->create(['programme_id' => $programme->id]);
        });


        factory(College::class)->create([
            'code' => 'DU-ANDC-001',
            'name' => 'Acharya Narendra Dev College'
            ]);

        factory(College::class)->create([
            'code'=> 'DU-KMV-002' ,
            'name' => 'Keshav Mahavidyalaya'
        ]);

        factory(College::class, 5)->create();
    }
}
