<?php

namespace Tests\Unit;

use App\Http\Requests\ExternalAuthorityUpdateRequest;
use App\Models\AdvisoryMeeting;
use App\Models\Cosupervisor;
use App\Models\ExternalAuthority;
use App\Models\Leave;
use App\Models\PhdCourse;
use App\Models\Pivot\ScholarCosupervisor;
use App\Models\Presentation;
use App\Models\ProgressReport;
use App\Models\Publication;
use App\Models\Scholar;
use App\Models\ScholarAdvisor;
use App\Models\ScholarAppeal;
use App\Models\ScholarDocument;
use App\Models\ScholarEducationDegree;
use App\Models\ScholarEducationInstitute;
use App\Models\ScholarEducationSubject;
use App\Models\User;
use App\Types\CitationIndex;
use App\Types\EducationInfo;
use App\Types\PrePhdCourseType;
use App\Types\PublicationType;
use App\Types\ScholarAppealTypes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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

        $this->assertInstanceOf(HasMany::class, $scholar->advisors());

        $cosupervisor = factory(ScholarCosupervisor::class)->states('user')->create();
        $external = create(ExternalAuthority::class);

        $scholar->advisors()->createMany([
            [
                'advisor_type' => $cosupervisor->person_type,
                'advisor_id' => $cosupervisor->person_id,
            ],
            [
                'advisor_type' => ExternalAuthority::class,
                'advisor_id' => $external->id,
            ],
        ]);

        $scholar->refresh();
        $this->assertCount(2, $scholar->advisors);
        $this->assertInstanceOf(ScholarAdvisor::class, $scholar->advisors->first());
    }

    /** @test */
    public function scholar_has_many_current_scholar_advisors()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasMany::class, $scholar->currentAdvisors());

        $oldAdvisors = create(ScholarCosupervisor::class, 2)
            ->map(function ($cosup) {
                return [
                    'advisor_type' => $cosup->person_type,
                    'advisor_id' => $cosup->person_id,
                    'started_on' => today()->subMonths(10),
                    'ended_on' => today()->subMonths(5),
                ];
            })->toArray();
        $oldAdvisors = $scholar->advisors()->createMany($oldAdvisors);

        $currentAdvisors = create(ScholarCosupervisor::class, 2)
            ->map(function ($cosup) {
                return [
                    'advisor_type' => $cosup->person_type,
                    'advisor_id' => $cosup->person_id,
                    'started_on' => today()->subMonths(5),
                ];
            })->toArray();
        $currentAdvisors = $scholar->advisors()->createMany($currentAdvisors);

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
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
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
    public function phdSeminarAppeals_method_returns_all_phd_seminar_appeals_of_the_scholar()
    {
        $scholar = create(Scholar::class);

        $phdSeminarAppeals = create(ScholarAppeal::class, 3, [
            'scholar_id' => $scholar->id,
            'type' => ScholarAppealTypes::PRE_PHD_SEMINAR,
        ]);

        $freshScholar = $scholar->fresh();

        $this->assertEquals($phdSeminarAppeals->pluck('id'), $freshScholar->phdSeminarAppeals()->pluck('id'));
    }

    /** @test */
    public function currentPhdSeminarAppeal_method_returns_the_latest_phd_seminar_appeal_of_the_scholar()
    {
        $scholar = create(Scholar::class);

        $phdSeminarAppealOld = create(ScholarAppeal::class, 1, [
            'scholar_id' => $scholar->id,
            'type' => ScholarAppealTypes::PRE_PHD_SEMINAR,
        ]);

        Carbon::setTestNow($appealTime = now()->addDay());

        $phdSeminarAppealNew = create(ScholarAppeal::class, 1, [
            'scholar_id' => $scholar->id,
            'type' => ScholarAppealTypes::PRE_PHD_SEMINAR,
        ]);

        $freshScholar = $scholar->fresh();

        $this->assertEquals($phdSeminarAppealNew->id, $freshScholar->currentPhDSeminarAppeal()->id);
    }
}
