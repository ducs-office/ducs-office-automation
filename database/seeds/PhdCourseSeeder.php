<?php

use App\Types\PrePhdCourseType;
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
            ['code' => 'RCS001', 'type' => PrePhdCourseType::CORE, 'name' => 'Reseach Methodology'],
            ['code' => 'RCS002', 'type' => PrePhdCourseType::CORE, 'name' => 'Foundation of Computer Science'],
            ['code' => 'RCS003', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Combinatorial Optimization'],
            ['code' => 'RCS004', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Approximation Algorithms'],
            ['code' => 'RCS005', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Special Topics in Theoretical Computer Science'],
            ['code' => 'RCS006', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Algorithmic Graph Theory'],
            ['code' => 'RCS007', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Human Computer Interaction (HCI)'],
            ['code' => 'RCS008', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Information Hiding Techniques'],
            ['code' => 'RCS009', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Information Security'],
            ['code' => 'RCS010', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Special Topics in Artificial Intelligence (Multi-Agent Systems)'],
            ['code' => 'RCS011', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Special Topics in Computational Intelligence'],
            ['code' => 'RCS012', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Special Topics in Computer Networks'],
            ['code' => 'RCS013', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Special Topics in Data Mining'],
            ['code' => 'RCS014', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Special Topics in Database System'],
            ['code' => 'RCS015', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Special Topics in Information Security'],
            ['code' => 'RCS016', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Special Topics in Soft Computing'],
            ['code' => 'RCS017', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Swarm Intelligence'],
            ['code' => 'RCS018', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Theory of NP Completeness'],
            ['code' => 'RCS019', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Software Quality Assuarance'],
            ['code' => 'RCS020', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Empirical Research Methods and Studies in Software Engineering'],
            ['code' => 'RCS021', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Deep Learning'],
            ['code' => 'RCS022', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Neural Networks'],
            ['code' => 'RCS023', 'type' => PrePhdCourseType::ELECTIVE, 'name' => 'Machine Learning '],
        ];

        DB::table('phd_courses')->insert($data);
    }
}
