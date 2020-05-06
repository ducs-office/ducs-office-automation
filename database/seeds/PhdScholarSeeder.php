<?php

use App\Models\Cosupervisor;
use App\Models\PhdCourse;
use App\Models\Scholar;
use App\Models\ScholarEducationDegree;
use App\Models\ScholarEducationInstitute;
use App\Models\ScholarEducationSubject;
use App\Models\Teacher;
use App\Models\User;
use App\Types\AdmissionMode;
use App\Types\AdvisoryCommitteeMember;
use App\Types\CitationIndex;
use App\Types\EducationInfo;
use App\Types\Gender;
use App\Types\PresentationEventType;
use App\Types\ReservationCategory;
use App\Types\UserType;
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
            'type' => UserType::FACULTY_TEACHER,
        ])->supervisorProfile()->create();

        $cosupervisorNaveen = factory(Cosupervisor::class)->create([
            'professor_type' => User::class,
            'professor_id' => factory(User::class)->create([
                'name' => 'Naveen Kumar',
                'email' => 'naveen@cs.du.ac.in',
                'type' => UserType::FACULTY_TEACHER,
            ])->id,
        ]);

        $scholarRajni = factory(Scholar::class)->create([
            'first_name' => 'Rajni',
            'last_name' => '.',
            'email' => 'rajni@cs.du.ac.in',
            'phone_no' => '9650361897',
            'gender' => Gender::FEMALE,
            'address' => 'HNo. 313, VPO Rani Khera, Delhi-110081',
            'category' => ReservationCategory::GENERAL,
            'admission_mode' => AdmissionMode::JRF,
            'research_area' => 'Theoretical Computer Science',
            'enrollment_date' => '2019-11-11',
            'supervisor_profile_id' => $supervisorNeelima->id,
            'cosupervisor_profile_type' => null,
            'cosupervisor_profile_id' => null,
            'advisory_committee' => [
                AdvisoryCommitteeMember::fromExistingCosupervisors($cosupervisorNaveen),
                new AdvisoryCommitteeMember('external', [
                    'name' => 'Naveen Garg',
                    'designation' => 'Professor',
                    'affiliation' => 'Indian Institute of Technology Delhi',
                    'email' => 'naveengarg@iitd.ac.in',
                ]),
            ],
            'education_details' => [
                new EducationInfo([
                    'degree' => factory(ScholarEducationDegree::class)->create([
                        'name' => 'BSc(H)',
                    ])->name,
                    'subject' => factory(ScholarEducationSubject::class)->create([
                        'name' => 'Computer Science',
                    ])->name,
                    'institute' => factory(ScholarEducationInstitute::class)->create([
                        'name' => 'Shyama Prasad Mukherji College, DU',
                    ])->name,
                    'year' => '2017',
                ]),
                new EducationInfo([
                    'degree' => factory(ScholarEducationDegree::class)->create([
                        'name' => 'MSc',
                    ])->name,

                    'subject' => 'Computer Science',

                    'institute' => factory(ScholarEducationInstitute::class)->create([
                        'name' => 'University of Delhi',
                    ])->name,
                    'year' => '2019',
                ]),
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

        $cosupervisorPoonam = factory(Cosupervisor::class)->create([
            'professor_type' => User::class,
            'professor_id' => factory(User::class)->create([
                'name' => 'Poonam Bedi',
                'email' => 'pbedi@cs.du.ac.in',
                'type' => UserType::FACULTY_TEACHER,
            ])->id,
        ]);

        $scholarSudhir = factory(Scholar::class)->create([
            'first_name' => 'Sudhir Kumar',
            'last_name' => 'Gupta',
            'email' => 'cs.sudhirg@gmail.com',
            'phone_no' => '9891304971',
            'gender' => Gender::MALE,
            'address' => 'D8-24, POCKET-2, SECTOR-G2, NARELA, DELHI-110040',
            'category' => ReservationCategory::OBC,
            'admission_mode' => AdmissionMode::UGC_NET,
            'research_area' => 'Mobile Specific Testing Strategies',
            'enrollment_date' => '2019-12-10',
            'supervisor_profile_id' => $supervisorSangeeta->id,
            'cosupervisor_profile_type' => null,
            'cosupervisor_profile_id' => null,
            'advisory_committee' => [
                AdvisoryCommitteeMember::fromExistingCosupervisors($cosupervisorPoonam),
                new AdvisoryCommitteeMember('external', [
                    'name' => 'V.B. Singh',
                    'designation' => 'Dr.',
                    'affiliation' => 'Delhi College of Arts & Commerce, University of Delhi',
                    'email' => 'vbsingh@gmail.com',
                ]),
                new AdvisoryCommitteeMember('external', [
                    'name' => 'Naveen Garg',
                    'designation' => 'Professor',
                    'affiliation' => 'Indian Institute of Technology Delhi',
                    'email' => 'naveen@.iitd.ac.in',
                ]),
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
                'indexed_in' => CitationIndex::SCOPUS,
                'page_numbers' => [29, 43],
            ],
        ]);

        //=======================================

        $scholarSapna = factory(Scholar::class)->create([
            'first_name' => 'Sapna',
            'last_name' => 'Grover',
            'email' => 'sapna.grover5@gmail.com',
            'phone_no' => '8447903161',
            'gender' => Gender::FEMALE,
            'address' => '8/22, Third Floor, Subhash Nagar, New Delhi-110027',
            'category' => ReservationCategory::GENERAL,
            'admission_mode' => AdmissionMode::DU_TEACHER,
            'research_area' => 'Approximation Algoriithms and their Analysis',
            'enrollment_date' => '2017-04-05',
            'supervisor_profile_id' => $supervisorNeelima->id,
            'cosupervisor_profile_type' => null,
            'cosupervisor_profile_id' => null,
            'advisory_committee' => [
                AdvisoryCommitteeMember::fromExistingCosupervisors($cosupervisorNaveen),
                new AdvisoryCommitteeMember('external', [
                    'name' => 'Naveen Garg',
                    'affiliation' => 'Department of Computer Science and Engineering IIT Delhi',
                    'designation' => 'Prof.',
                    'email' => 'naveengarg@iitd.ac.in',
                ]),
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
            'indexed_in' => CitationIndex::SCOPUS,
            'page_numbers' => [3, 37],
            'city' => 'Ahmedabad, Gujarat',
            'country' => 'India',
        ])->presentations()->create([
            'scholar_id' => $scholarSapna->id,
            'city' => 'Ahmedabad, Gujarat',
            'country' => 'India',
            'date' => '2018-12-11',
            'event_type' => PresentationEventType::CONFERENCE,
            'event_name' => 'Presentation',
        ]);

        //======================================

        $supervisorArchana = factory(Teacher::class)->create([
            'first_name' => 'Archana',
            'last_name' => 'Singhal',
            'email' => 'archanasinghal1970@gmail.com',
        ])->supervisorProfile()->create();

        $cosupervisorMuttoo = factory(Cosupervisor::class)->create([
            'professor_type' => User::class,
            'professor_id' => factory(User::class)->create([
                'name' => 'S.K Muttoo',
                'email' => 'drskmuttoo@gmail.com',
            ])->id,
        ]);

        $scholarNisha = factory(Scholar::class)->create([
            'first_name' => 'Nisha',
            'last_name' => '.',
            'email' => 'nisha1988.d@gmail.com',
            'phone_no' => '9868335426',
            'gender' => Gender::FEMALE,
            'address' => 'WZ 247 Street no 7, Sadh Nagar Palam Colony, New Delhi- 110045',
            'category' => ReservationCategory::SC,
            'admission_mode' => AdmissionMode::UGC_NET,
            'research_area' => 'Information Security',
            'enrollment_date' => '2018-11-26',
            'supervisor_profile_id' => $supervisorArchana->id,
            'cosupervisor_profile_id' => $cosupervisorMuttoo->id,
            'cosupervisor_profile_type' => Cosupervisor::class,
            'advisory_committee' => [
                AdvisoryCommitteeMember::fromExistingCosupervisors($cosupervisorPoonam),
                new AdvisoryCommitteeMember('external', [
                    'name' => 'Archana Singhal',
                    'affiliation' => 'Ip college, University of Delhi',
                    'designation' => 'Dr.',
                    'email' => 'archana@ip.du.ac.in',
                ]),
                new AdvisoryCommitteeMember('external', [
                    'name' => 'Harmeet Kaur',
                    'designation' => 'Professor',
                    'affiliation' => 'Hansraj college, University of Delhi',
                    'email' => 'harmeet@hc.du.ac.in',
                ]),
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
            'professor_type' => Teacher::class,
            'professor_id' => factory(Teacher::class)->create([
                'first_name' => 'Anurag',
                'last_name' => 'Mishra',
                'email' => 'anurag_cse2003@yahoo.com',
            ])->id,
        ]);

        $scholarMegha = factory(Scholar::class)->create([
            'first_name' => 'Megha',
            'last_name' => 'Bansal',
            'email' => 'megha.cs.du@gmail.com',
            'phone_no' => '9990278679',
            'gender' => Gender::FEMALE,
            'address' => 'G-73, Saket, New Delhi-17',
            'category' => ReservationCategory::GENERAL,
            'admission_mode' => AdmissionMode::UGC_NET,
            'research_area' => 'Information Security',
            'enrollment_date' => '2018-05-12',
            'supervisor_profile_id' => $supervisorArpita->id,
            'cosupervisor_profile_id' => $cosupervisorAnurag->id,
            'cosupervisor_profile_type' => Cosupervisor::class,
            'advisory_committee' => [
                AdvisoryCommitteeMember::fromExistingCosupervisors($cosupervisorPoonam),
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
            'gender' => Gender::MALE,
            'address' => '76/59 Ganeshpuram, Prayagraj. U.P. 211002',
            'category' => ReservationCategory::GENERAL,
            'admission_mode' => AdmissionMode::JRF,
            'research_area' => 'Machine Learning',
            'enrollment_date' => '2019-06-11',
            'supervisor_profile_id' => $supervisorSangeeta->id,
            'advisory_committee' => [
                AdvisoryCommitteeMember::fromExistingCosupervisors($cosupervisorPoonam),
                new AdvisoryCommitteeMember('external', [
                    'name' => 'V.B. Singh',
                    'designation' => 'Dr.',
                    'affiliation' => 'Delhi College of Arts & Commerce, University of Delhi',
                    'email' => 'vbsingh@gmail.com',
                ]),
            ],
        ]);

        $scholarKountay->courseworks()->attach([
            'phd_course_id' => 23,
        ]);
    }
}
