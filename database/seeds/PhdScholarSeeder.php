<?php

use App\Models\College;
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
use App\Types\Designation;
use App\Types\EducationInfo;
use App\Types\Gender;
use App\Types\PresentationEventType;
use App\Types\PublicationType;
use App\Types\ReservationCategory;
use App\Types\UserCategory;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PhdScholarSeeder extends Seeder
{
    const ENC_PASSWORD = '$2y$10$bRgtu7JbF6VVbR9FG6E5oeTyP.Hi2w./HQ51t.WnP1cJFmjpMQ4y2';

    protected $faculty;
    protected $college_teachers;
    protected $externals;
    protected $nocPath;
    protected $documentPath;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createFacultyTeachers();
        $this->createCollegeTeachers();
        $this->createExternals();
        $this->createDocuments();

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
        $rajni->supervisors()->attach($this->faculty->neelima_gupta);
        $rajni->advisors()->attach([
            $this->externals->naveen_garg->id,
            $this->faculty->naveen_kumar->id,
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
        ]);
        $sudhir->supervisors()->attach($this->college_teachers->sangeeta_srivastava);
        $sudhir->advisors()->attach([
            $this->faculty->poonam_bedi->id,
            $this->externals->vb_singh->id,
        ]);
        $sudhir->courseworks()->attach(
            PhdCourse::whereCode('RCS023')->first()
        );
        $sudhirPublication = $sudhir->publications()->create([
            'type' => PublicationType::JOURNAL,
            'name' => 'International Journal of Recent Technology and Engineering',
            'paper_title' => 'Big Data Analytics: An Indian Perspective',
            'date' => '2019-09-30',
            'volume' => 8,
            'number' => 3,
            'indexed_in' => CitationIndex::SCOPUS,
            'page_numbers' => [29, 43],
            'is_published' => true,
            'document_path' => $this->documentPath,
        ]);
        $sudhirPublication->coAuthors()->createMany([
            [
                'name' => 'Ashish Kumar Jha',
                'noc_path' => $this->nocPath,
            ],
            [
                'name' => 'Ajay Kumar',
                'noc_path' => $this->nocPath,
            ],
            [
                'name' => 'Mahesh Kumar Chaubey',
                'noc_path' => $this->nocPath,
            ],
            [
                'name' => 'Jitendra Singh',
                'noc_path' => $this->nocPath,
            ],
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
        ]);
        $sapna->supervisors()->attach($this->faculty->neelima_gupta);
        $sapna->advisors()->attach([
            $this->faculty->naveen_kumar->id,
            $this->externals->naveen_garg->id,
        ]);
        $sapna->courseworks()->attach(
            PhdCourse::whereCode('RCS003')->first()
        );
        $sapnaPublication = $sapna->publications()->create([
            'type' => PublicationType::CONFERENCE,
            'name' => 'Foundations of Software Technology and Theoretical Computer Science (FSTTCS) 2018',
            'paper_title' => 'Constant factor Approximation Algorithm for Uniform Hard Capacitated Knapsack Median Problem',
            'date' => '2018-12-11',
            'volume' => 122,
            'indexed_in' => CitationIndex::SCOPUS,
            'page_numbers' => [3, 37],
            'city' => 'Ahmedabad, Gujarat',
            'country' => 'India',
            'is_published' => true,
            'document_path' => $this->documentPath,
        ]);
        $sapnaPublication->coAuthors()->createMany([
            [
                'name' => 'Neelima Gupta',
                'noc_path' => $this->nocPath,
            ],
            [
                'name' => 'Aditya Pancholi',
                'noc_path' => $this->nocPath,
            ],
            [
                'name' => 'Samir Khuller',
                'noc_path' => $this->nocPath,
            ],
        ]);
        $sapna->presentations()->create([
            'scholar_id' => $sapna->id,
            'publication_id' => $sapnaPublication->id,
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
        ]);
        $nisha->supervisors()->attach($this->college_teachers->archana_singhal);
        $nisha->cosupervisors()->attach($this->faculty->sk_mutto);
        $nisha->advisors()->attach([
            $this->faculty->poonam_bedi->id,
            $this->externals->harmeet_kaur->id,
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
        ]);
        $megha->supervisors()->attach($this->college_teachers->arpita_sharma);
        $megha->cosupervisors()->attach($this->college_teachers->anurag_mishra);
        $megha->advisors()->attach($this->faculty->poonam_bedi);
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
        ]);
        $kountay->supervisors()->attach($this->college_teachers->sangeeta_srivastava);
        $kountay->advisors()->attach([
            $this->faculty->poonam_bedi->id,
            $this->externals->vb_singh->id,
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
                'category' => UserCategory::FACULTY_TEACHER,
                'password' => self::ENC_PASSWORD,
                'is_supervisor' => true,
            ]),

            'naveen_kumar' => User::create([
                'name' => 'Naveen Kumar',
                'email' => 'naveen@cs.du.ac.in',
                'category' => UserCategory::FACULTY_TEACHER,
                'password' => self::ENC_PASSWORD,
                'is_supervisor' => true,
            ]),

            'poonam_bedi' => User::create([
                'name' => 'Poonam Bedi',
                'email' => 'pbedi@cs.du.ac.in',
                'category' => UserCategory::FACULTY_TEACHER,
                'password' => self::ENC_PASSWORD,
                'is_supervisor' => true,
            ]),

            'sk_mutto' => User::create([
                'name' => 'S.K Muttoo',
                'email' => 'drskmuttoo@gmail.com',
                'category' => UserCategory::FACULTY_TEACHER,
                'password' => self::ENC_PASSWORD,
                'is_supervisor' => true,
                'is_cosupervisor' => true,
            ]),
        ];
    }

    public function createCollegeTeachers()
    {
        $this->college_teachers = (object) [
            'sangeeta_srivastava' => User::create([
                'first_name' => 'Sangeeta',
                'last_name' => 'Srivastava',
                'email' => 'sangeeta.srivastava@cas.du.ac.in',
                'password' => self::ENC_PASSWORD,
                'category' => UserCategory::COLLEGE_TEACHER,
                'is_supervisor' => true,
            ]),

            'archana_singhal' => User::create([
                'first_name' => 'Archana',
                'last_name' => 'Singhal',
                'email' => 'archanasinghal1970@gmail.com',
                'password' => self::ENC_PASSWORD,
                'category' => UserCategory::COLLEGE_TEACHER,
                'is_supervisor' => true,
            ]),

            'arpita_sharma' => User::create([
                'first_name' => 'Arpita',
                'last_name' => 'Sharma',
                'email' => 'asharma@ddu.du.ac.in',
                'password' => self::ENC_PASSWORD,
                'category' => UserCategory::COLLEGE_TEACHER,
                'is_supervisor' => true,
            ]),

            'anurag_mishra' => User::create([
                'first_name' => 'Anurag',
                'last_name' => 'Mishra',
                'email' => 'anurag_cse2003@yahoo.com',
                'password' => self::ENC_PASSWORD,
                'category' => UserCategory::COLLEGE_TEACHER,
                'is_cosupervisor' => true,
            ]),
        ];
    }

    public function createExternals()
    {
        $this->externals = (object) [
            'naveen_garg' => User::create([
                'first_name' => 'Naveen',
                'last_name' => 'Garg',
                'email' => 'naveen@.iitd.ac.in',
                'password' => self::ENC_PASSWORD,
                'designation' => Designation::PROFESSOR,
                'category' => UserCategory::EXTERNAL,
                'affiliation' => 'Indian Institute of Technology Delhi',
                'is_cosupervisor' => true,
            ]),

            'vb_singh' => User::create([
                'first_name' => 'VB',
                'last_name' => 'Singh',
                'email' => 'vbsingh@gmail.com',
                'password' => self::ENC_PASSWORD,
                'designation' => Designation::PROFESSOR,
                'category' => UserCategory::COLLEGE_TEACHER,
                'college_id' => College::firstOrCreate([
                    'code' => 'DU-DCAC',
                    'name' => 'Delhi College of Arts & Commerce, University of Delhi',
                    'principal_name' => 'Anuradha Gupta',
                    'principal_emails' => ['principaldcac@gmail.com'],
                    'principal_phones' => ['1234567890'],
                    'address' => 'New Moti Bagh, Netaji Nagar, New Delhi, Delhi 110023',
                ])->id,
            ]),

            'harmeet_kaur' => User::create([
                'first_name' => 'Harmeet',
                'last_name' => 'Kaur',
                'email' => 'harmeet@hc.du.ac.in',
                'password' => self::ENC_PASSWORD,
                'designation' => Designation::PROFESSOR,
                'category' => UserCategory::COLLEGE_TEACHER,
                'college_id' => College::firstOrCreate([
                    'code' => 'DU-HRC',
                    'name' => 'Hansraj College, University of Delhi',
                ])->id,
            ]),
        ];
    }

    public function createDocuments()
    {
        Storage::fake();

        $this->nocPath = UploadedFile::fake()
            ->create('noc.pdf', '10', 'application/pdf')
            ->store('publications/co_authors_noc');
        $this->documentPath = UploadedFile::fake()
            ->create('file.pdf', '10', 'application/pdf')
            ->store('publications');
    }
}
