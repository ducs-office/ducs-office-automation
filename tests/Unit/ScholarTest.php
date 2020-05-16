<?php

namespace Tests\Unit;

use App\Models\AdvisoryMeeting;
use App\Models\Cosupervisor;
use App\Models\Leave;
use App\Models\PhdCourse;
use App\Models\Presentation;
use App\Models\ProgressReport;
use App\Models\Publication;
use App\Models\Scholar;
use App\Models\ScholarAppeal;
use App\Models\ScholarDocument;
use App\Models\ScholarEducationDegree;
use App\Models\ScholarEducationInstitute;
use App\Models\ScholarEducationSubject;
use App\Models\SupervisorProfile;
use App\Models\User;
use App\Types\CitationIndex;
use App\Types\EducationInfo;
use App\Types\PrePhdCourseType;
use App\Types\PublicationType;
use App\Types\ScholarAppealTypes;
use App\Types\ScholarDocumentType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ScholarTest extends TestCase
{
    use RefreshDatabase;

    protected function fillPublication($overrides = [])
    {
        return $this->mergeFormFields([
            'type' => null,
            'name' => 'India CS Journal',
            'paper_title' => 'Lorem ipsum dolor sit amet consectetur adipisicing',
            'date' => '2020-02-09',
            'volume' => '1',
            'page_numbers' => ['23', '80'],
            'indexed_in' => ['Scopus', 'SCI'],
            'number' => null,
            'publisher' => null,
            'city' => null,
            'country' => null,
        ], $overrides);
    }

    /** @test */
    public function scholar_has_many_publications()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(MorphMany::class, $scholar->publications());

        $scholar->publications()->createMany([
            $this->fillPublication([
                'type' => PublicationType::JOURNAL,
                'number' => 123,
                'publisher' => 'O Reilly',
            ]),
            $this->fillPublication([
                'type' => PublicationType::CONFERENCE,
                'city' => 'Delhi',
                'country' => 'India',
            ]),
        ]);

        $this->assertCount(2, $scholar->publications);
    }

    /** @test */
    public function scholar_belongs_to_a_supervisor_profile()
    {
        $supervisorProfile = create(SupervisorProfile::class);

        $scholar = create(Scholar::class, 1, [
            'supervisor_profile_id' => $supervisorProfile->id,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $scholar->supervisorProfile());
        $this->assertTrue($supervisorProfile->is($scholar->supervisorProfile));
    }

    /** @test */
    public function scholar_morphs_to_a_supervisor_indirectly_through_supervisor_profile()
    {
        $supervisorProfile = create(SupervisorProfile::class);

        $scholar = create(Scholar::class, 1, [
            'supervisor_profile_id' => $supervisorProfile->id,
        ]);

        $this->assertInstanceOf(MorphTo::class, $scholar->supervisor());
        $this->assertTrue($supervisorProfile->supervisor->is($scholar->supervisor));
    }

    /** @test */
    public function scholar_has_many_pre_phd_courseworks()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(BelongsToMany::class, $scholar->courseworks());
        $this->assertCount(0, $scholar->courseworks);

        $scholar->courseworks()->attach(create(PhdCourse::class));

        $this->assertCount(1, $scholar->fresh()->courseworks);
    }

    /** @test */
    public function scholar_has_many_leaves()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasMany::class, $scholar->leaves());
        $this->assertCount(0, $scholar->leaves);

        $leaves = create(Leave::class, 2, ['scholar_id' => $scholar->id]);
        create(Leave::class, 2, ['scholar_id' => $scholar->id, 'extended_leave_id' => $leaves[0]->id]);

        $this->assertCount(count($leaves), $scholar->fresh()->leaves);
        $this->assertEquals($leaves->sortByDesc('to')->pluck('id'), $scholar->fresh()->leaves->pluck('id'));
    }

    /** @test */
    public function all_core_courseworks_are_added_to_scholar_when_scholar_is_created()
    {
        $coreCourseworks = create(PhdCourse::class, 2, ['type' => PrePhdCourseType::CORE]);
        $electiveCourseworks = create(PhdCourse::class, 2, ['type' => PrePhdCourseType::ELECTIVE]);

        $scholar = create(Scholar::class);

        $this->assertCount(2, $scholar->courseworks);
        $this->assertEquals(
            $coreCourseworks->pluck('id'),
            $scholar->courseworks->pluck('id')
        );
    }

    /** @test */
    public function scholar_has_many_advisory_meetings()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasMany::class, $scholar->advisoryMeetings());
        $this->assertCount(0, $scholar->advisoryMeetings);

        $meeting = create(AdvisoryMeeting::class, 1, ['scholar_id' => $scholar->id]);

        $this->assertCount(1, $scholar->fresh()->advisoryMeetings);
    }

    /** @test */
    public function scholar_cosupervisor_profile_is_either_supervisor__profile_or_cosupervisor_via_morphs_to()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(MorphTo::class, $scholar->cosupervisorProfile());

        $cosupervisor = create(Cosupervisor::class);
        $scholar = create(Scholar::class, 1, [
            'cosupervisor_profile_type' => Cosupervisor::class,
            'cosupervisor_profile_id' => $cosupervisor->id,
        ]);

        $this->assertTrue($cosupervisor->is($scholar->cosupervisorProfile));

        $supervisorProfile = create(SupervisorProfile::class);
        $scholar = create(Scholar::class, 1, [
            'cosupervisor_profile_type' => SupervisorProfile::class,
            'cosupervisor_profile_id' => $supervisorProfile->id,
        ]);

        $this->assertTrue($supervisorProfile->is($scholar->cosupervisorProfile));
    }

    /** @test */
    public function cosupervisor_return_supervisor_if_scholar_cosupervisor_profile_type_is_supervisor_profile()
    {
        $supervisorProfile = create(SupervisorProfile::class);

        $scholar = create(Scholar::class, 1, [
            'cosupervisor_profile_type' => SupervisorProfile::class,
            'cosupervisor_profile_id' => $supervisorProfile->id,
        ]);

        $supervisor = $supervisorProfile->supervisor;

        $this->assertEquals($supervisor->name, $scholar->cosupervisor->name);
        $this->assertEquals($supervisor->email, $scholar->cosupervisor->email) ;
        $this->assertEquals($supervisor->profile->designation ?? 'Professor', $scholar->cosupervisor->designation);
        $this->assertEquals(
            $supervisor->supervisor_type === User::class ? 'DUCS' :
                $supervisor->profile->college->name ?? 'Affiliation Not Set',
            $scholar->cosupervisor->affiliation
        );
    }

    /** @test */
    public function cosupervisor_return_cosupervisor_if_scholar_cosupervisor_profile_type_is_cosupervisor_profile()
    {
        $cosupervisor = create(Cosupervisor::class);
        $scholar = create(Scholar::class, 1, [
            'cosupervisor_profile_type' => Cosupervisor::class,
            'cosupervisor_profile_id' => $cosupervisor->id,
        ]);

        $this->assertTrue($cosupervisor->is($scholar->cosupervisor));
    }

    /** @test */
    public function scholar_has_many_presentations()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasMany::class, $scholar->presentations());

        $this->assertCount(0, $scholar->presentations);

        $presentation = create(Presentation::class, 1, [
            'scholar_id' => $scholar->id,
        ]);

        $this->assertCount(1, $scholar->fresh()->presentations);
    }

    /** @test */
    public function register_on_attribute_return_date_when_scholar_is_created()
    {
        $scholar = new Scholar();

        $scholar->created_at = $createdAt = now();
        $registerOn = $createdAt->format('d F Y');

        $this->assertEquals($registerOn, $scholar->register_on);
    }

    public function scholar_advisory_committee_is_returned_as_an_array_containing_supervisor_and_cosupervisor()
    {
        $faculty = create(User::class, 1, ['category' => 'faculty_teacher']);
        $supervisor = $faculty->supervisorProfile()->create();

        $scholar = create(Scholar::class, 1, [
            'supervisor_profile_id' => $supervisor->id,
        ]);

        $cosupervisor = create(Cosupervisor::class);

        $scholar->cosupervisors()->attach([
            'cosupervisor_id' => $cosupervisor->id,
        ]);

        $advisoryCommittee = $scholar->advisory_committee;

        $this->assertArrayHasKey('supervisor', $advisoryCommittee);
        $this->assertEquals($faculty->name, $advisoryCommittee['supervisor']);

        $this->assertArrayHasKey('cosupervisor', $advisoryCommittee);
        $this->assertEquals($cosupervisor->name, $advisoryCommittee['cosupervisor']);
    }

    /** @test */
    public function scholar_advisory_committee_does_not_have_a_cosupervisor_field_if_the_scholar_does_mot_have_a_cosupervisor()
    {
        $scholar = create(Scholar::class, 1, [
            'cosupervisor_profile_id' => null,
            'cosupervisor_profile_type' => null,
        ]);

        $advisoryCommittee = $scholar->advisory_committee;

        $this->assertArrayNotHasKey('cosupervisor', $advisoryCommittee);
    }

    /** @test */
    public function scholar_education_is_as_an_empty_array_if_education_details_are_null_on_scholar_creation()
    {
        $this->signInScholar($scholar = create(Scholar::class, 1, ['education_details' => null]));

        $this->assertEquals([], $scholar->education_details);
    }

    /** @test */
    public function scholar_has_many_documents()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasMany::class, $scholar->documents());
        $this->assertCount(0, $scholar->documents);

        $documents = create(ScholarDocument::class, 2, ['scholar_id' => $scholar->id]);

        $this->assertCount(count($documents), $scholar->fresh()->documents);
        $this->assertEquals($documents->sortByDesc('date')->pluck('id'), $scholar->fresh()->documents->pluck('id'));
    }

    /** @test */
    public function scholar_has_many_progress_reports()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasMany::class, $scholar->progressReports());
        $this->assertCount(0, $scholar->progressReports);

        $progressReports = create(ProgressReport::class, 2, ['scholar_id' => $scholar->id]);

        $updatedScholar = $scholar->fresh();
        $this->assertCount(count($progressReports), $updatedScholar->progressReports);
        $this->assertEquals($progressReports->sortByDesc('date')->pluck('id'), $updatedScholar->progressReports->pluck('id'));
    }

    /** @test */
    public function addCourse_methods_assigns_pivot_attributes()
    {
        Storage::fake();
        $marksheetPath = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf')->store('scholar_marksheets');

        $course = create(PhdCourse::class);
        $scholar = create(Scholar::class);

        $scholar->addCourse($course, [
            'marksheet_path' => $marksheetPath,
            'completed_on' => $completeDate = now()->format('Y-m-d'),
        ]);

        $scholar = $scholar->fresh();

        $this->assertCount(1, $scholar->courseworks);
        $this->assertEquals($course->id, $scholar->courseworks->first()->pivot->phd_course_id);
        $this->assertEquals($marksheetPath, $scholar->courseworks->first()->pivot->marksheet_path);
        $this->assertEquals($completeDate, $scholar->courseworks->first()->pivot->completed_on->format('Y-m-d'));
    }

    /** @test */
    public function addCourse_methods_add_course_without_pivot_attributes()
    {
        $scholar = create(Scholar::class);
        $course = create(PhdCourse::class);

        $scholar->addCourse($course);

        $scholar = $scholar->fresh();

        $this->assertCount(1, $scholar->courseworks);
        $this->assertEquals($course->id, $scholar->courseworks->first()->pivot->phd_course_id);
        $this->assertNull($scholar->courseworks->first()->pivot->marksheet_path);
        $this->assertNull($scholar->courseworks->first()->pivot->completed_on);
    }

    /** @test */
    public function scholar_has_many_appeals()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasMany::class, $scholar->appeals());
        $this->assertCount(0, $scholar->appeals);

        $scholarAppeals = create(ScholarAppeal::class, 2, ['scholar_id' => $scholar->id]);

        $freshScholar = $scholar->fresh();

        $this->assertCount(2, $freshScholar->appeals);
        $this->assertEquals($scholarAppeals->pluck('id'), $freshScholar->appeals->pluck('id'));
    }

    /** @test */
    public function countSCIOrSCIEJournals_returns_number_of_SCI_and_SCIE_journal_publications()
    {
        $scholar = Create(Scholar::class);

        $publicationsSCIAndSCIE = create(Publication::class, 2, [
            'type' => PublicationType::JOURNAL,
            'indexed_in' => [CitationIndex::SCI, CitationIndex::SCIE],
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $publicationSCIAndMR = create(publication::class, 2, [
            'type' => PublicationType::JOURNAL,
            'indexed_in' => [CitationIndex::SCI, CitationIndex::MR],
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $publciationSCIEAndSCOPUS = create(publication::class, 2, [
            'type' => PublicationType::JOURNAL,
            'indexed_in' => [CitationIndex::SCIE, CitationIndex::SCOPUS],
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $publicationSCOPUSAndMR = create(Publication::class, 1, [
            'type' => PublicationType::JOURNAL,
            'indexed_in' => [CitationIndex::SCOPUS, CitationIndex::MR],
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $ConferencePublicationSCIAndSCIE = create(Publication::class, 3, [
            'type' => PublicationType::CONFERENCE,
            'indexed_in' => [CitationIndex::SCI, CitationIndex::SCIE],
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $this->assertCount(10, $scholar->publications);
        $this->assertCount(7, $scholar->journals);
        $this->assertEquals(6, $scholar->CountSCIOrSCIEJournals());
    }

    /** @test */
    public function countMRPublications_return_number_of_indexed_in_MR_publications()
    {
        $scholar = Create(Scholar::class);

        $publicationsSCIAndSCIE = create(Publication::class, 2, [
            'indexed_in' => [CitationIndex::SCI, CitationIndex::SCIE],
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $publicationSCIAndMR = create(publication::class, 2, [
            'indexed_in' => [CitationIndex::SCI, CitationIndex::MR],
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $publciationMR = create(publication::class, 2, [
            'indexed_in' => [CitationIndex::MR],
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $this->assertCount(6, $scholar->publications);
        $this->assertEquals(4, $scholar->CountMRPublications());
    }

    /** @test */
    public function countScopusNotSCIOrSCIE_return_number_of_publications_which_are_scopus_indexed_but_not_SCI_or_SCIE_indexed()
    {
        $scholar = Create(Scholar::class);

        $publicationsSCISCIEAndSCOPUS = create(Publication::class, 2, [
            'indexed_in' => [CitationIndex::SCI, CitationIndex::SCIE, CitationIndex::MR],
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $publicationSCIAndSCOPUS = create(publication::class, 2, [
            'indexed_in' => [CitationIndex::SCI, CitationIndex::SCOPUS],
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $publciationSCOPUS = create(publication::class, 2, [
            'indexed_in' => [CitationIndex::SCOPUS],
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $publicationSCOPUSAndMR = create(publication::class, 2, [
            'indexed_in' => [CitationIndex::SCOPUS, CitationIndex::MR],
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $publicationMR = create(publication::class, 2, [
            'indexed_in' => [CitationIndex::MR],
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $publicationSCOPUSAndSCIE = create(publication::class, 2, [
            'indexed_in' => [CitationIndex::SCOPUS, CitationIndex::SCIE],
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $this->assertCount(12, $scholar->publications);
        $this->assertEquals(4, $scholar->CountScopusNotSCIOrSCIEPublications());
    }

    /** @test */
    public function phdSeminarAppeals_method_returns_the_latest_applied_phd_seminar_appeal()
    {
        $scholar = create(Scholar::class);

        $scholarAppealOld = create(ScholarAppeal::class, 1, [
            'scholar_id' => $scholar->id,
            'applied_on' => '2011-10-10',
            'type' => ScholarAppealTypes::PRE_PHD_SEMINAR,
        ]);

        $scholarAppealNew = create(ScholarAppeal::class, 1, [
            'scholar_id' => $scholar->id,
            'applied_on' => '2020-10-10',
            'type' => ScholarAppealTypes::PRE_PHD_SEMINAR,
        ]);

        $freshScholar = $scholar->fresh();

        $this->assertEquals($scholarAppealNew->id, $freshScholar->phdSeminarAppeal()->id);
    }
}
