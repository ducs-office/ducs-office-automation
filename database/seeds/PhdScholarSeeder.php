<?php

use App\Cosupervisor;
use App\PhdCourse;
use App\Scholar;
use App\ScholarEducationSubject;
use App\Teacher;
use App\User;
use Illuminate\Database\Seeder;

class PhdScholarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supervisorNeelima = factory(User::class)->create([
            'name' => 'Neelima Gupta',
            'email' => 'ngupta.cs.du@gmail.com',
        ])->supervisorProfile()->create();

        $scholarRajni = factory(Scholar::class)->create([
            'first_name' => 'Rajni',
            'last_name' => '.',
            'email' => 'rajni@cs.du.ac.in',
            'phone_no' => '9650361897',
            'gender' => 'F',
            'address' => 'HNo. 313, VPO Rani Khera, Delhi-110081',
            'category' => 'G',
            'admission_via' => 'J',
            'research_area' => 'Theoretical Computer Science',
            'enrollment_date' => '2019-11-11',
            'supervisor_profile_id' => $supervisorNeelima->id,
            'advisory_committee' => [
                [
                    'name' => 'Neelima Gupta',
                    'title' => 'Prof',
                    'affiliation' => 'University of Delhi',
                    'designation' => 'Permanent',
                ],
                [
                    'name' => 'Naveen kumar',
                    'title' => 'Prof',
                    'affiliation' => 'University of Delhi',
                    'designation' => 'Permanent',
                ],
                [
                    'name' => 'Naveen Garg',
                    'title' => 'Prof',
                    'affiliation' => 'Indian Institute of Technology Delhi',
                    'designation' => 'Permanent',
                ],
            ],
            'education' => [
                [
                    'degree' => 1,
                    'subject' => 1,
                    'institute' => 1,
                    'year' => '2017',
                ],
                [
                    'degree' => 2,
                    'subject' => 1,
                    'institute' => 2,
                    'year' => '2019',
                ],
            ],
        ]);

        $scholarRajni->courseworks()->attach([
            'phd_course_id' => 4,
        ]);

        // ====================================

        $supervisorSangeeta = factory(Teacher::class)->create([
            'first_name' => 'Sangeeta',
            'last_name' => 'Srivastava',
            'email' => 'sangeeta.srivastava@cas.du.ac.in',
        ])->supervisorProfile()->create();

        $scholarSudhir = factory(Scholar::class)->create([
            'first_name' => 'Sudhir Kumar',
            'last_name' => 'Gupta',
            'email' => 'cs.sudhirg@gmail.com',
            'phone_no' => '9891304971',
            'gender' => 'M',
            'address' => 'D8-24, POCKET-2, SECTOR-G2, NARELA, DELHI-110040',
            'category' => 'O',
            'admission_via' => 'NET',
            'research_area' => 'Mobile Specific Testing Strategies',
            'enrollment_date' => '2019-12-10',
            'supervisor_profile_id' => $supervisorSangeeta->id,
            'advisory_committee' => [
                [
                    'name' => 'Punam Bedi',
                    'title' => 'Prof',
                    'affiliation' => 'University of Delhi',
                    'designation' => 'Permanent',
                ],
                [
                    'name' => 'V.B. Singh',
                    'title' => 'Dr',
                    'affiliation' => 'Delhi College of Arts & Commerce, University of Delhi',
                    'designation' => 'Permanent',
                ],
                [
                    'name' => 'Naveen Garg',
                    'title' => 'Prof',
                    'affiliation' => 'Indian Institute of Technology Delhi',
                    'designation' => 'Permanent',
                ],
            ],
        ]);

        $scholarSudhir->courseworks()->attach([
            'phd_course_id' => 23,
        ]);

        $scholarSudhir->publications()->createMany([
            [
                'type' => 'journal',
                'name' => 'International Journal of Recent Technology and Engineering',
                'paper_title' => 'Big Data Analytics: An Indian Perspective',
                'authors' => [
                    'Ashish Kumar Jha', 'Sudhir Kumar Gupta',
                    'Ajay Kumar', 'Mahesh Kumar Chaubey',
                    'Jitendra Singh',
                ],
                'date' => '2019-09-30',
                'volume' => 8,
                'number' => 3,
                'indexed_in' => 'Scopus',
                'page_numbers' => [29, 43],
            ],
            [
                'type' => 'journal',
                'name' => 'International Journal of Computer Applications',
                'paper_title' => 'Grid-based Image Encryption using RSA',
                'authors' => [
                    'Binay Kumar Singh', 'Sudhir Kumar Gupta',
                ],
                'date' => '2015-04-30',
                'volume' => 115,
                'number' => null,
                'indexed_in' => 'G',
                'page_numbers' => [2 - 6],
            ],
        ]);

        //=======================================

        $scholarSapna = factory(Scholar::class)->create([
            'first_name' => 'Sapna',
            'last_name' => 'Grover',
            'email' => 'sapna.grover5@gmail.com',
            'phone_no' => '8447903161',
            'gender' => 'F',
            'address' => '8/22, Third Floor, Subhash Nagar, New Delhi-110027',
            'category' => 'G',
            'admission_via' => 'T',
            'research_area' => 'Approximation Algoriithms and their Analysis',
            'enrollment_date' => '2017-04-05',
            'supervisor_profile_id' => $supervisorNeelima->id,
            'advisory_committee' => [
                [
                    'name' => 'Naveen Kumar',
                    'title' => 'Prof',
                    'affiliation' => 'University of Delhi',
                    'designation' => 'Permanent',
                ],
                [
                    'name' => 'Naveen Garg',
                    'title' => 'Prof',
                    'affiliation' => 'Department of Computer Science and Engineering IIT Delhi',
                    'designation' => 'Permanent',
                ],
            ],
        ]);

        $scholarSapna->courseworks()->attach([
            'phd_course_id' => 3,
        ]);

        $scholarSapna->publications()->create([
            'type' => 'conference',
            'name' => 'Foundations of Software Technology and Theoretical Computer Science (FSTTCS) 2018',
            'paper_title' => 'Constant factor Approximation Algorithm for Uniform Hard Capacitated Knapsack Median Problem',
            'authors' => [
                'Sapna Grover', 'Neelima Gupta',
                'Samir Khuller', 'Aditya Pancholi',
            ],
            'date' => '2018-12-11',
            'volume' => 122,
            'indexed_in' => 'Scopus',
            'page_numbers' => [3, 37],
            'city' => 'Ahmedabad, Gujarat',
            'country' => 'India',
        ])->presentations()->create([
            'scholar_id' => $scholarSapna->id,
            'city' => 'Ahmedabad, Gujarat',
            'country' => 'India',
            'date' => '2018-12-11',
            'event_type' => 'C',
            'event_name' => 'Presentation',
        ]);

        //======================================

        $supervisorArchana = factory(Teacher::class)->create([
            'first_name' => 'Archana',
            'last_name' => 'Singhal',
            'email' => 'archanasinghal1970@gmail.com',
        ])->supervisorProfile()->create();

        $cosupervisorMuttoo = factory(Cosupervisor::class)->create([
            'name' => 'S.K Muttoo',
            'email' => 'drskmuttoo@gmail.com',
            'affiliation' => 'Department of Computer Science, University of Delhi',
            'designation' => 'Permanent',
        ]);

        $scholarNisha = factory(Scholar::class)->create([
            'first_name' => 'Nisha',
            'last_name' => '.',
            'email' => 'nisha1988.d@gmail.com',
            'phone_no' => '9868335426',
            'gender' => 'F',
            'address' => 'WZ 247 Street no 7, Sadh Nagar Palam Colony, New Delhi- 110045',
            'category' => 'SC',
            'admission_via' => 'NET',
            'research_area' => 'Information Security',
            'enrollment_date' => '2018-11-26',
            'supervisor_profile_id' => $supervisorArchana->id,
            'cosupervisor_id' => $cosupervisorMuttoo->id,
            'advisory_committee' => [
                [
                    'name' => 'Archana Singhal',
                    'title' => 'Dr',
                    'affiliation' => 'Ip college, University of Delhi',
                    'designation' => 'Permanent',
                ],
                [
                    'name' => 'S.K Muttoo',
                    'title' => 'Dr',
                    'affiliation' => 'Indian Institute of Technology Delhi',
                    'designation' => 'Permanent',
                ],
                [
                    'name' => 'Poonam Bedi',
                    'title' => 'Prof',
                    'affiliation' => 'Department of Computer Science, University of Delhi',
                    'designation' => 'Permanent',
                ],
                [
                    'name' => 'Harmeet Kaur',
                    'title' => 'Dr',
                    'affiliation' => 'Hansraj college, University of Delhi',
                    'designation' => 'Permanent',
                ],
            ],
        ]);

        $scholarNisha->courseworks()->attach([
            'phd_course_id' => 8,
        ]);

        //======================================

        $supervisorArpita = factory(Teacher::class)->create([
            'first_name' => 'Arpita',
            'last_name' => 'Sharma',
            'email' => 'asharma@ddu.du.ac.in',
        ])->supervisorProfile()->create();

        $cosupervisorAnurag = factory(Cosupervisor::class)->create([
            'name' => 'Anurag Mishra',
            'email' => 'anurag_cse2003@yahoo.com',
            'affiliation' => 'Deen Dayal Upadhyaya College, Delhi University',
            'designation' => 'Permanent',
        ]);

        $scholarMegha = factory(Scholar::class)->create([
            'first_name' => 'Megha',
            'last_name' => 'Bansal',
            'email' => 'megha.cs.du@gmail.com',
            'phone_no' => '9990278679',
            'gender' => 'F',
            'address' => 'G-73, Saket, New Delhi-17',
            'category' => 'G',
            'admission_via' => 'NET',
            'research_area' => 'Information Security',
            'enrollment_date' => '2018-05-12',
            'supervisor_profile_id' => $supervisorArpita->id,
            'cosupervisor_id' => $cosupervisorAnurag->id,
            'advisory_committee' => [
                [
                    'name' => 'Poonam Bedi',
                    'title' => 'Prof',
                    'affiliation' => 'Department of Computer Science, University of Delhi',
                    'designation' => 'Permanent',
                ],
                [
                    'name' => 'Naveen kumar',
                    'title' => 'Prof',
                    'affiliation' => 'University of Delhi',
                    'designation' => 'Permanent',
                ],
            ],
        ]);

        $scholarMegha->courseworks()->attach([
            'phd_course_id' => 8,
        ]);

        //=======================================

        $scholarKountay = factory(Scholar::class)->create([
            'first_name' => 'Kountay',
            'last_name' => 'Dwivedi',
            'email' => 'kdwivedi@cs.du.ac.in',
            'phone_no' => '9650220569',
            'gender' => 'M',
            'address' => '76/59 Ganeshpuram, Prayagraj. U.P. 211002',
            'category' => 'G',
            'admission_via' => 'J',
            'research_area' => 'Machine Learning',
            'enrollment_date' => '2019-06-11',
            'supervisor_profile_id' => $supervisorSangeeta->id,
            'advisory_committee' => [
                [
                    'name' => 'Poonam Bedi',
                    'title' => 'Prof',
                    'affiliation' => 'Department of Computer Science, University of Delhi',
                    'designation' => 'Permanent',
                ],
                [
                    'name' => 'V.B. Singh',
                    'title' => 'Dr',
                    'affiliation' => 'Delhi College of Arts & Commerce, University of Delhi',
                    'designation' => 'Permanent',
                ],
            ],
        ]);

        $scholarKountay->courseworks()->attach([
            'phd_course_id' => 23,
        ]);
    }
}
