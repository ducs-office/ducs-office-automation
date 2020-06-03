<?php

namespace Tests\Unit;

use App\Models\AdvisoryMeeting;
use App\Models\Leave;
use App\Models\PhdCourse;
use App\Models\Pivot\ScholarCosupervisor;
use App\Models\PrePhdSeminar;
use App\Models\Presentation;
use App\Models\ProgressReport;
use App\Models\Publication;
use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Models\ScholarExaminer;
use App\Models\TitleApproval;
use App\Models\User;
use App\Types\CitationIndex;
use App\Types\PrePhdCourseType;
use App\Types\PublicationType;
use App\Types\RequestStatus;
use App\Types\ScholarDocumentType;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ScholarTest extends TestCase
{
    use RefreshDatabase;

    protected function fillPublication($overrides = [])
    {
        Storage::fake();

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
            'is_published' => true,
            'document_path' => UploadedFile::fake()->create('file.pdf', 10, 'application/pdf')->store('/publications'),
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
    public function scholar_has_many_supervisors()
    {
        $scholar = create(Scholar::class);
        $this->assertInstanceOf(BelongsToMany::class, $scholar->supervisors());

        $supervisor = factory(User::class)->states('supervisor')->create();
        $scholar->supervisors()->attach($supervisor);

        $supervisors = $scholar->supervisors()
            ->wherePivot('supervisor_id', $supervisor->id)
            ->get();

        $this->assertCount(1, $supervisors);
    }

    /** @test */
    public function scholar_has_one_currentSupervisor()
    {
        $scholar = create(Scholar::class);

        $supervisors = factory(User::class, 2)->states('supervisor')->create();
        $scholar->supervisors()->attach([
            $supervisors[0]->id => ['started_on' => today()->subMonths(8), 'ended_on' => today()->subMonths(3)],
            $supervisors[1]->id => ['started_on' => today()->subMonths(3), 'ended_on' => null],
        ]);

        $scholar->refresh();

        $this->assertCount(2, $scholar->supervisors);

        $this->assertNotNull($scholar->currentSupervisor, 'current supervisor should not be null');
        $this->assertEquals($supervisors[1]->id, $scholar->currentSupervisor->id);
    }

    /** @test */
    public function scholar_has_many_cosupervisors()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(BelongsToMany::class, $scholar->cosupervisors());

        $cosupervisor = factory(User::class)->states('cosupervisor')->create();
        $scholar->cosupervisors()->attach($cosupervisor);

        $relatedCosupervisors = $scholar->cosupervisors()->get();
        $this->assertCount(1, $relatedCosupervisors);
        $this->assertInstanceOf(User::class, $relatedCosupervisors->first());
        $this->assertInstanceOf(ScholarCosupervisor::class, $relatedCosupervisors->first()->pivot);
    }

    /** @test */
    public function scholar_has_one_currentCosupervisor()
    {
        $scholar = create(Scholar::class);

        $oldCosupervisor = factory(ScholarCosupervisor::class)->create([
            'scholar_id' => $scholar->id,
            'started_on' => today()->subMonths(8),
            'ended_on' => today()->subMonths(3),
        ]);
        $currentCosupervisor = factory(ScholarCosupervisor::class)->create([
            'scholar_id' => $scholar->id,
            'started_on' => today()->subMonths(3),
        ]);

        $scholar->refresh();

        $this->assertCount(2, $scholar->cosupervisors);

        $scholarsCurrentCosupervisor = $scholar->currentCosupervisor;
        $this->assertInstanceOf(User::class, $scholarsCurrentCosupervisor);
    }

    /** @test */
    public function scholar_has_many_scholar_advisors()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(BelongsToMany::class, $scholar->advisors());

        $cosupervisor = factory(User::class)->states('cosupervisor')->create();
        $external = factory(User::class)->states('external')->create();

        $scholar->advisors()->attach([$cosupervisor->id, $external->id]);

        $scholar->refresh();
        $this->assertCount(2, $scholar->advisors);
        $this->assertInstanceOf(User::class, $scholar->advisors->first());
    }

    /** @test */
    public function getAvatarPath_gives_accessible_avatar_url()
    {
        $scholar = create(Scholar::class);

        $gravatar = 'https://gravatar.com/avatar/'
            . md5(strtolower(trim($scholar->email)))
            . '?s=200&d=identicon';

        $this->assertEquals($gravatar, $scholar->getAvatarUrl());

        $scholar->avatar_path = '/avatars/file/thatdoesntexist.jpg';
        $this->assertEquals($gravatar, $scholar->getAvatarUrl());

        Storage::fake();
        $scholar->avatar_path = UploadedFile::fake()->image('file.jpg')->store('/avatars');
        Storage::assertExists($scholar->avatar_path);
        $this->assertEquals(route('scholars.profile.avatar', $scholar), $scholar->getAvatarUrl());
    }

    /** @test */
    public function scholar_has_many_current_scholar_advisors()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(BelongsToMany::class, $scholar->currentAdvisors());

        $oldAdvisors = factory(User::class, 2)->states('external')->create();
        $scholar->advisors()->attach($oldAdvisors, [
            'started_on' => today()->subMonths(10),
            'ended_on' => today()->subMonths(5),
        ]);

        $currentAdvisors = factory(User::class, 2)->states('external')->create();
        $scholar->advisors()->attach($currentAdvisors, [
            'started_on' => today()->subMonths(5),
        ]);

        $scholar->refresh();

        $this->assertCount(2, $scholar->currentAdvisors);
        $this->assertEquals($currentAdvisors->pluck('id')->toArray(), $scholar->currentAdvisors->pluck('id')->toArray());
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
    public function scholar_has_many_presentations()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasMany::class, $scholar->presentations());

        $this->assertCount(0, $scholar->presentations);

        $publication = create(Publication::class, 1, [
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $presentation = create(Presentation::class, 1, [
            'scholar_id' => $scholar->id,
            'publication_id' => $publication->id,
        ]);

        $this->assertCount(1, $scholar->fresh()->presentations);
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
    public function countSCIOrSCIEJournals_returns_number_of_SCI_and_SCIE_journal_publications()
    {
        $scholar = Create(Scholar::class);

        $publicationsSCIAndSCIE = create(Publication::class, 2, [
            'type' => PublicationType::JOURNAL,
            'indexed_in' => [CitationIndex::SCI, CitationIndex::SCIE],
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $publicationSCIAndMR = create(publication::class, 2, [
            'type' => PublicationType::JOURNAL,
            'indexed_in' => [CitationIndex::SCI, CitationIndex::MR],
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $publciationSCIEAndSCOPUS = create(publication::class, 2, [
            'type' => PublicationType::JOURNAL,
            'indexed_in' => [CitationIndex::SCIE, CitationIndex::SCOPUS],
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $publicationSCOPUSAndMR = create(Publication::class, 1, [
            'type' => PublicationType::JOURNAL,
            'indexed_in' => [CitationIndex::SCOPUS, CitationIndex::MR],
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $ConferencePublicationSCIAndSCIE = create(Publication::class, 3, [
            'type' => PublicationType::CONFERENCE,
            'indexed_in' => [CitationIndex::SCI, CitationIndex::SCIE],
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
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
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $publicationSCIAndMR = create(publication::class, 2, [
            'indexed_in' => [CitationIndex::SCI, CitationIndex::MR],
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $publciationMR = create(publication::class, 2, [
            'indexed_in' => [CitationIndex::MR],
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
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
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $publicationSCIAndSCOPUS = create(publication::class, 2, [
            'indexed_in' => [CitationIndex::SCI, CitationIndex::SCOPUS],
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $publciationSCOPUS = create(publication::class, 2, [
            'indexed_in' => [CitationIndex::SCOPUS],
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $publicationSCOPUSAndMR = create(publication::class, 2, [
            'indexed_in' => [CitationIndex::SCOPUS, CitationIndex::MR],
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $publicationMR = create(publication::class, 2, [
            'indexed_in' => [CitationIndex::MR],
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $publicationSCOPUSAndSCIE = create(publication::class, 2, [
            'indexed_in' => [CitationIndex::SCOPUS, CitationIndex::SCIE],
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
        ]);

        $this->assertCount(12, $scholar->publications);
        $this->assertEquals(4, $scholar->CountScopusNotSCIOrSCIEPublications());
    }

    /** @test */
    public function scholar_has_one_pre_phd_seminar()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasOne::class, $scholar->prePhdSeminar());

        $prePhdSeminar = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
        ]);

        $freshScholar = $scholar->fresh();

        $this->assertTrue($prePhdSeminar->is($freshScholar->prePhdSeminar));
    }

    /** @test */
    public function areCourseworksCompleted_method_returns_are_all_courseworks_of_scholar_complete_or_not()
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

        $this->assertTrue($scholar->areCourseworksCompleted());

        $course = create(PhdCourse::class);

        $scholar->addCourse($course);

        $scholar = $scholar->fresh();

        $this->assertFalse($scholar->areCourseworksCompleted());
    }

    /** @test */
    public function canApplyForPrePhdSeminar_method_returns_true_if_all_documents_are_uploaded_journal_publications_are_added_courseworks_are_complete_and_proposed_title_is_added()
    {
        $scholar = create(Scholar::class, 1, [
            'proposed_title' => Str::random(20),
        ]);

        $this->assertFalse($scholar->canApplyForPrePhdSeminar());

        Storage::fake();
        $document = UploadedFile::fake()->create('joining_letter.pdf', 20, 'application/pdf');

        create(ScholarDocument::class, 1, [
            'scholar_id' => $scholar->id,
            'path' => $document,
            'type' => ScholarDocumentType::JOINING_LETTER,
        ]);

        $this->assertFalse($scholar->fresh()->canApplyForPrePhdSeminar());

        create(Publication::class, 1, [
            'author_type' => Scholar::class,
            'author_id' => $scholar->id,
            'type' => PublicationType::JOURNAL,
        ]);

        $marksheetPath = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf')->store('scholar_marksheets');

        $course = create(PhdCourse::class);

        $scholar->addCourse($course);

        $this->assertFalse($scholar->fresh()->canApplyForPrePhdSeminar());

        $scholar->courseworks()->updateExistingPivot($course->id, [
            'marksheet_path' => $marksheetPath,
            'completed_on' => $completeDate = now()->format('Y-m-d'),
        ]);

        $scholar = $scholar->fresh();

        $this->assertTrue($scholar->canApplyForPrePhdSeminar());
    }

    /** @test */
    public function scholar_has_one_title_approval()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasOne::class, $scholar->titleApproval());

        $titleApproval = create(TitleApproval::class, 1, [
            'scholar_id' => $scholar->id,
        ]);

        $freshScholar = $scholar->fresh();

        $this->assertTrue($titleApproval->is($freshScholar->titleApproval));
    }

    /** @test */
    public function canApplyForTitleApproval_method_returns_true_if_all_documents_are_uploaded_and_finalized_title_is_added()
    {
        $scholar = create(Scholar::class);

        create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPROVED,
            'finalized_title' => Str::random(20),
        ]);

        $this->assertFalse($scholar->canApplyForTitleApproval());

        Storage::fake();
        $document = UploadedFile::fake()->create('joining_letter.pdf', 20, 'application/pdf');

        create(ScholarDocument::class, 1, [
            'scholar_id' => $scholar->id,
            'path' => $document,
            'type' => ScholarDocumentType::JOINING_LETTER,
        ]);

        $this->assertFalse($scholar->fresh()->canApplyForTitleApproval());

        $document = UploadedFile::fake()->create('thesis_table_of_content.pdf', 20, 'application/pdf');

        create(ScholarDocument::class, 1, [
            'scholar_id' => $scholar->id,
            'path' => $document,
            'type' => ScholarDocumentType::THESIS_TOC,
        ]);

        $this->assertFalse($scholar->fresh()->canApplyForTitleApproval());

        $document = UploadedFile::fake()->create('pre_phd_seminar_notice.pdf', 20, 'application/pdf');

        create(ScholarDocument::class, 1, [
            'scholar_id' => $scholar->id,
            'path' => $document,
            'type' => ScholarDocumentType::PRE_PHD_SEMINAR_NOTICE,
        ]);

        $this->assertTrue($scholar->fresh()->canApplyForTitleApproval());
    }

    /** @test */
    public function isJoiningLetterUploaded_method_return_true_if_joining_letter_of_scholar_is_uploaded()
    {
        $scholar = create(Scholar::class);

        $this->assertFalse($scholar->isJoiningLetterUploaded());

        Storage::fake();
        $file = UploadedFile::fake()->create('joining_letter.pdf', 20, 'application/pdf');

        create(ScholarDocument::class, 1, [
            'path' => $file,
            'type' => ScholarDocumentType::JOINING_LETTER,
            'scholar_id' => $scholar->id,
        ]);

        $this->assertTrue($scholar->isJoiningLetterUploaded());
    }

    /** @test */
    public function isTableOfContentsOfThesisUploaded_method_return_true_if_scholar_document_of_type_THESIS_TOC_uploaded()
    {
        $scholar = create(Scholar::class);

        $this->assertFalse($scholar->isTableOfContentsOfThesisUploaded());

        Storage::fake();
        $file = UploadedFile::fake()->create('thesis_toc.pdf', 20, 'application/pdf');

        create(ScholarDocument::class, 1, [
            'path' => $file,
            'type' => ScholarDocumentType::THESIS_TOC,
            'scholar_id' => $scholar->id,
        ]);

        $this->assertTrue($scholar->isTableOfContentsOfThesisUploaded());
    }

    /** @test */
    public function isPrePhdSeminarNoticeUploaded_method_return_true_if_scholar_document_of_type_PRE_PHD_SEMINAR_NOTICE_uploaded()
    {
        $scholar = create(Scholar::class);

        $this->assertFalse($scholar->isPrePhdSeminarNoticeUploaded());

        Storage::fake();
        $file = UploadedFile::fake()->create('pre_phd_seminar_notice.pdf', 20, 'application/pdf');

        create(ScholarDocument::class, 1, [
            'path' => $file,
            'type' => ScholarDocumentType::PRE_PHD_SEMINAR_NOTICE,
            'scholar_id' => $scholar->id,
        ]);

        $this->assertTrue($scholar->isPrePhdSeminarNoticeUploaded());
    }

    /** @test */
    public function registrationValidUpto_returns_the_date_to_which_the_scholars_registration_is_valid()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $registrationValidUpto = $scholar->registration_date->addYears(
            $scholar->term_duration
        );

        $this->assertEquals($registrationValidUpto, $scholar->registrationValidUpto());
    }

    /** @test */
    public function scholar_has_one_scholar_examiner()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasOne::class, $scholar->examiner());

        $scholarExaminer = create(ScholarExaminer::class, 1, [
            'scholar_id' => $scholar->id,
        ]);

        $this->assertTrue($scholarExaminer->is($scholar->examiner));
    }
}
