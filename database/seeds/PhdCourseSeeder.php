<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhdCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['code' => 'RCS001', 'type' => 'C', 'name' => 'Reseach Methodology'],
            ['code' => 'RCS002', 'type' => 'C', 'name' => 'Foundation of Computer Science'],
            ['code' => 'RCS003', 'type' => 'E', 'name' => 'Combinatorial Optimization'],
            ['code' => 'RCS004', 'type' => 'E', 'name' => 'Approximation Algorithms'],
            ['code' => 'RCS005', 'type' => 'E', 'name' => 'Special Topics in Theoretical Computer Science'],
            ['code' => 'RCS006', 'type' => 'E', 'name' => 'Algorithmic Graph Theory'],
            ['code' => 'RCS007', 'type' => 'E', 'name' => 'Human Computer Interaction (HCI)'],
            ['code' => 'RCS008', 'type' => 'E', 'name' => 'Information Hiding Techniques'],
            ['code' => 'RCS009', 'type' => 'E', 'name' => 'Information Security'],
            ['code' => 'RCS010', 'type' => 'E', 'name' => 'Special Topics in Artificial Intelligence (Multi-Agent Systems)'],
            ['code' => 'RCS011', 'type' => 'E', 'name' => 'Special Topics in Computational Intelligence'],
            ['code' => 'RCS012', 'type' => 'E', 'name' => 'Special Topics in Computer Networks'],
            ['code' => 'RCS013', 'type' => 'E', 'name' => 'Special Topics in Data Mining'],
            ['code' => 'RCS014', 'type' => 'E', 'name' => 'Special Topics in Database System'],
            ['code' => 'RCS015', 'type' => 'E', 'name' => 'Special Topics in Information Security'],
            ['code' => 'RCS016', 'type' => 'E', 'name' => 'Special Topics in Soft Computing'],
            ['code' => 'RCS017', 'type' => 'E', 'name' => 'Swarm Intelligence'],
            ['code' => 'RCS018', 'type' => 'E', 'name' => 'Theory of NP Completeness'],
            ['code' => 'RCS019', 'type' => 'E', 'name' => 'Software Quality Assuarance'],
            ['code' => 'RCS020', 'type' => 'E', 'name' => 'Empirical Research Methods and Studies in Software Engineering'],
            ['code' => 'RCS021', 'type' => 'E', 'name' => 'Deep Learning'],
            ['code' => 'RCS022', 'type' => 'E', 'name' => 'Neural Networks'],
            ['code' => 'RCS023', 'type' => 'E', 'name' => 'Machine Learning '],
        ];

        DB::table('phd_courses')->insert($data);
    }
}
