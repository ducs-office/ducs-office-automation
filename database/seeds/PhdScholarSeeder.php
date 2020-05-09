<?php

use App\Models\Cosupervisor;
use App\Models\PhdCourse;
use App\Models\Scholar;
use App\Models\ScholarEducationDegree;
use App\Models\ScholarEducationInstitute;
use App\Models\ScholarEducationSubject;
use App\Models\SupervisorProfile;
use App\Models\Teacher;
use App\Models\User;
use App\Types\AdmissionMode;
use App\Types\AdvisoryCommitteeMember;
use App\Types\CitationIndex;
use App\Types\EducationInfo;
use App\Types\Gender;
use App\Types\PresentationEventType;
use App\Types\PublicationType;
use App\Types\ReservationCategory;
use App\Types\UserType;
use Illuminate\Database\Seeder;

class PhdScholarSeeder extends Seeder
{
    const ENC_PASSWORD = '$2y$10$bRgtu7JbF6VVbR9FG6E5oeTyP.Hi2w./HQ51t.WnP1cJFmjpMQ4y2';

    protected $faculty;
    protected $college_teachers;
    protected $supervisors;
    protected $cosupervisors;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createFacultyTeachers();
        $this->createCollegeTeachers();
        $this->createSupervisors();
        $this->createCosupervisors();

        // =========== Rajni Dabbas ============
        $rajni = Scholar::create([
            'first_name' => 'Rajni',
            'last_name' => 'Dabbas',
            'email' => 'rajni@cs.du.ac.in',
            'term_duration' => 5,
            'password' => self::ENC_PASSWORD,
            'phone_no' => '9650361897',
            'gender' => Gender::FEMALE,
            'address' => 'HNo. 313, VPO Rani Khera, Delhi-110081',
            'category' => ReservationCategory::GENERAL,
            'admission_mode' => AdmissionMode::JRF,
            'research_area' => 'Theoretical Computer Science',
            'registration_date' => '2019-11-11',
            'supervisor_profile_id' => $this->supervisors->neelima_gupta->id,
            'advisory_committee' => [
                AdvisoryCommitteeMember::fromExistingSupervisors(
                    $this->supervisors->naveen_kumar
                ),
                AdvisoryCommitteeMember::fromExistingCosupervisors(
                    $this->cosupervisors->naveen_garg
                ),
            ],
            'education_details' => [
                new EducationInfo([
                    'degree' => ScholarEducationDegree::create([
                        'name' => 'B.Sc (H)',
                    ])->name,
                    'subject' => ScholarEducationSubject::create([
                        'name' => 'Computer Science',
                    ])->name,
                    'institute' => ScholarEducationInstitute::create([
                        'name' => 'Shyama Prasad Mukherji College (University of Delhi)',
                    ])->name,
                    'year' => '2017',
                ]),
                new EducationInfo([
                    'degree' => ScholarEducationDegree::create([
                        'name' => 'M.Sc',
                    ])->name,
                    'subject' => 'Computer Science',
                    'institute' => ScholarEducationInstitute::create([
                        'name' => 'Department of Computer Science, University of Delhi',
                    ])->name,
                    'year' => '2019',
                ]),
            ],
        ]);
        $rajni->courseworks()->attach(PhdCourse::whereCode('RCS004')->first());

        // ============ Sudhir Gupta =============
        $sudhir = Scholar::create([
            'first_name' => 'Sudhir',
            'last_name' => 'Gupta',
            'email' => 'cs.sudhirg@gmail.com',
            'term_duration' => 5,
            'password' => self::ENC_PASSWORD,
            'phone_no' => '9891304971',
            'gender' => Gender::MALE,
            'address' => 'D8-24, POCKET-2, SECTOR-G2, NARELA, DELHI-110040',
            'category' => ReservationCategory::OBC,
            'admission_mode' => AdmissionMode::UGC_NET,
            'research_area' => 'Mobile Specific Testing Strategies',
            'registration_date' => '2019-12-10',
            'supervisor_profile_id' => $this->supervisors->sangeeta_srivastava->id,
            'advisory_committee' => [
                AdvisoryCommitteeMember::fromExistingSupervisors(
                    $this->supervisors->poonam_bedi
                ),
                AdvisoryCommitteeMember::fromExistingCosupervisors(
                    $this->cosupervisors->naveen_garg
                ),
                new AdvisoryCommitteeMember('external', [
                    'name' => 'V.B. Singh',
                    'designation' => 'Dr.',
                    'affiliation' => 'Delhi College of Arts & Commerce, University of Delhi',
                    'email' => 'vbsingh@gmail.com',
                ]),
            ],
        ]);
        $sudhir->courseworks()->attach(
            PhdCourse::whereCode('RCS023')->first()
        );
        $sudhir->publications()->create([
            'type' => PublicationType::JOURNAL,
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
        ]);

        // ================== Sapna Grover =====================
        $sapna = Scholar::create([
            'first_name' => 'Sapna',
            'last_name' => 'Grover',
            'email' => 'sapna.grover5@gmail.com',
            'term_duration' => 5,
            'password' => self::ENC_PASSWORD,
            'phone_no' => '8447903161',
            'gender' => Gender::FEMALE,
            'address' => '8/22, Third Floor, Subhash Nagar, New Delhi-110027',
            'category' => ReservationCategory::GENERAL,
            'admission_mode' => AdmissionMode::DU_TEACHER,
            'research_area' => 'Approximation Algoriithms and their Analysis',
            'registration_date' => '2017-04-05',
            'supervisor_profile_id' => $this->supervisors->neelima_gupta->id,
            'advisory_committee' => [
                AdvisoryCommitteeMember::fromExistingSupervisors(
                    $this->supervisors->naveen_kumar
                ),
                AdvisoryCommitteeMember::fromExistingCoSupervisors(
                    $this->cosupervisors->naveen_garg
                ),
            ],
        ]);
        $sapna->courseworks()->attach(
            PhdCourse::whereCode('RCS003')->first()
        );
        $publication = $sapna->publications()->create([
            'type' => PublicationType::CONFERENCE,
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
        ]);
        $sapna->presentations()->create([
            'scholar_id' => $sapna->id,
            'publication_id' => $publication->id,
            'city' => 'Ahmedabad, Gujarat',
            'country' => 'India',
            'date' => '2018-12-11',
            'event_type' => PresentationEventType::CONFERENCE,
            'event_name' => 'ICSS',
        ]);

        //=================== Nisha ===================
        $nisha = factory(Scholar::class)->create([
            'first_name' => 'Nisha',
            'last_name' => '.',
            'email' => 'nisha1988.d@gmail.com',
            'term_duration' => 5,
            'phone_no' => '9868335426',
            'gender' => Gender::FEMALE,
            'address' => 'WZ 247 Street no 7, Sadh Nagar Palam Colony, New Delhi- 110045',
            'category' => ReservationCategory::SC,
            'admission_mode' => AdmissionMode::UGC_NET,
            'research_area' => 'Information Security',
            'registration_date' => '2018-11-26',
            'supervisor_profile_id' => $this->supervisors->archana_singhal->id,
            'cosupervisor_profile_id' => $this->supervisors->sk_mutto->id,
            'cosupervisor_profile_type' => SupervisorProfile::class,
            'advisory_committee' => [
                AdvisoryCommitteeMember::fromExistingSupervisors($this->supervisors->archana_singhal),
                AdvisoryCommitteeMember::fromExistingSupervisors($this->supervisors->poonam_bedi),
                new AdvisoryCommitteeMember('external', [
                    'name' => 'Harmeet Kaur',
                    'designation' => 'Professor',
                    'affiliation' => 'Hansraj college, University of Delhi',
                    'email' => 'harmeet@hc.du.ac.in',
                ]),
            ],
        ]);
        $nisha->courseworks()->attach(
            PhdCourse::whereCode('RCS008')->first()
        );

        //================== Megha Bansal ====================
        $megha = Scholar::create([
            'first_name' => 'Megha',
            'last_name' => 'Bansal',
            'email' => 'megha.cs.du@gmail.com',
            'term_duration' => 5,
            'password' => self::ENC_PASSWORD,
            'phone_no' => '9990278679',
            'gender' => Gender::FEMALE,
            'address' => 'G-73, Saket, New Delhi-17',
            'category' => ReservationCategory::GENERAL,
            'admission_mode' => AdmissionMode::UGC_NET,
            'research_area' => 'Information Security',
            'registration_date' => '2018-05-12',
            'supervisor_profile_id' => $this->supervisors->arpita_sharma->id,
            'cosupervisor_profile_id' => $this->cosupervisors->anurag_mishra->id,
            'cosupervisor_profile_type' => Cosupervisor::class,
            'advisory_committee' => [
                AdvisoryCommitteeMember::fromExistingSupervisors(
                    $this->supervisors->poonam_bedi
                ),
            ],
        ]);
        $megha->courseworks()->attach(
            PhdCourse::whereCode('RCS008')->first()
        );

        //=================== Kountay Dwivedi ====================
        $kountay = factory(Scholar::class)->create([
            'first_name' => 'Kountay',
            'last_name' => 'Dwivedi',
            'email' => 'kdwivedi@cs.du.ac.in',
            'term_duration' => 5,
            'phone_no' => '9650220569',
            'gender' => Gender::MALE,
            'address' => '76/59 Ganeshpuram, Prayagraj. U.P. 211002',
            'category' => ReservationCategory::GENERAL,
            'admission_mode' => AdmissionMode::JRF,
            'research_area' => 'Machine Learning',
            'registration_date' => '2019-06-11',
            'supervisor_profile_id' => $this->supervisors->sangeeta_srivastava->id,
            'advisory_committee' => [
                AdvisoryCommitteeMember::fromExistingSupervisors(
                    $this->supervisors->poonam_bedi
                ),
                new AdvisoryCommitteeMember('external', [
                    'name' => 'V.B. Singh',
                    'designation' => 'Dr.',
                    'affiliation' => 'Delhi College of Arts & Commerce, University of Delhi',
                    'email' => 'vbsingh@gmail.com',
                ]),
            ],
        ]);
        $kountay->courseworks()->attach(
            PhdCourse::whereCode('RCS023')->first()
        );
    }

    public function createFacultyTeachers()
    {
        $this->faculty = (object) [
            'neelima_gupta' => User::create([
                'name' => 'Neelima Gupta',
                'email' => 'ngupta.cs.du@gmail.com',
                'type' => UserType::FACULTY_TEACHER,
                'password' => self::ENC_PASSWORD,
            ]),

            'naveen_kumar' => User::create([
                'name' => 'Naveen Kumar',
                'email' => 'naveen@cs.du.ac.in',
                'type' => UserType::FACULTY_TEACHER,
                'password' => self::ENC_PASSWORD,
            ]),

            'poonam_bedi' => User::create([
                'name' => 'Poonam Bedi',
                'email' => 'pbedi@cs.du.ac.in',
                'type' => UserType::FACULTY_TEACHER,
                'password' => self::ENC_PASSWORD,
            ]),

            'sk_mutto' => User::create([
                'name' => 'S.K Muttoo',
                'email' => 'drskmuttoo@gmail.com',
                'type' => UserType::FACULTY_TEACHER,
                'password' => self::ENC_PASSWORD,
            ]),
        ];
    }

    public function createCollegeTeachers()
    {
        $this->college_teachers = (object) [
            'sangeeta_srivastava' => Teacher::create([
                'first_name' => 'Sangeeta',
                'last_name' => 'Srivastava',
                'email' => 'sangeeta.srivastava@cas.du.ac.in',
                'password' => self::ENC_PASSWORD,
            ]),

            'archana_singhal' => Teacher::create([
                'first_name' => 'Archana',
                'last_name' => 'Singhal',
                'email' => 'archanasinghal1970@gmail.com',
                'password' => self::ENC_PASSWORD,
            ]),

            'arpita_sharma' => Teacher::create([
                'first_name' => 'Arpita',
                'last_name' => 'Sharma',
                'email' => 'asharma@ddu.du.ac.in',
                'password' => self::ENC_PASSWORD,
            ]),

            'anurag_mishra' => Teacher::create([
                'first_name' => 'Anurag',
                'last_name' => 'Mishra',
                'email' => 'anurag_cse2003@yahoo.com',
                'password' => self::ENC_PASSWORD,
            ]),
        ];
    }

    public function createSupervisors()
    {
        $this->supervisors = (object) collect([
            'neelima_gupta' => 'faculty',
            'naveen_kumar' => 'faculty',
            'poonam_bedi' => 'faculty',
            'sk_mutto' => 'faculty',
            'sangeeta_srivastava' => 'college_teachers',
            'archana_singhal' => 'college_teachers',
            'arpita_sharma' => 'college_teachers',
        ])->map(function ($name, $type) {
            return $this->{$name}->{$type}->supervisorProfile()->create();
        })->all();
    }

    public function createCosupervisors()
    {
        $this->cosupervisors = (object) [
            'anurag_mishra' => Cosupervisor::create([
                'professor_type' => Teacher::class,
                'professor_id' => $this->college_teachers->anurag_mishra->id,
            ]),
            'naveen_garg' => Cosupervisor::create([
                'name' => 'Naveen Garg',
                'email' => 'naveen@.iitd.ac.in',
                'designation' => 'Professor',
                'affiliation' => 'Indian Institute of Technology Delhi',
            ]),
        ];
    }
}
