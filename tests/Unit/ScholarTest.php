<?php

namespace Tests\Unit;

use App\Models\AdvisoryMeeting;
use App\Models\Cosupervisor;
use App\Models\Leave;
use App\Models\PhdCourse;
use App\Models\Presentation;
use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Models\ScholarEducationDegree;
use App\Models\ScholarEducationInstitute;
use App\Models\ScholarEducationSubject;
use App\Models\SupervisorProfile;
use App\Models\User;
use App\Types\ScholarDocumentType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScholarTest extends TestCase
{
    use RefreshDatabase;

    protected function fillPublication($overrides = [])
    {
        return $this->mergeFormFields([
            'type' => null,
            'name' => 'India CS Journal',
            'authors' => ['JOhn Doe', 'Sally Brooke'],
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
                'type' => 'journal',
                'number' => 123,
                'publisher' => 'O Reilly',
            ]),
            $this->fillPublication([
                'type' => 'conference',
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
        $coreCourseworks = create(PhdCourse::class, 2, ['type' => 'C']);
        $electiveCourseworks = create(PhdCourse::class, 2, ['type' => 'E']);

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
    public function scholar_belongs_to_a_cosupervisor()
    {
        $cosupervisor = create(Cosupervisor::class);
        $scholar = create(Scholar::class, 1, ['cosupervisor_id' => $cosupervisor->id]);

        $this->assertInstanceOf(BelongsTo::class, $scholar->cosupervisor());
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
        $scholar = create(Scholar::class, 1, ['cosupervisor_id' => null]);

        $advisoryCommittee = $scholar->advisory_committee;

        $this->assertArrayNotHasKey('cosupervisor', $advisoryCommittee);
    }

    /** @test */
    public function scholar_education_is_as_an_empty_array_onscholar_creation()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $this->assertEquals([], $scholar->education);
    }

    /** @test */
    public function scholar_education_is_returned_as_an_array_containing_the_names_of_each_field_in_place_of_their_corresponding_ids()
    {
        $degree = create(ScholarEducationDegree::class);
        $subject = create(ScholarEducationSubject::class);
        $institute = create(ScholarEducationInstitute::class);

        $scholar = create(Scholar::class);

        $scholar->update([
            'education' => [
                [
                    'degree' => $degree->id,
                    'subject' => $subject->id,
                    'institute' => $institute->id,
                    'year' => $year = '2012',
                ],
            ],
        ]);

        $this->assertEquals(count($scholar->education), 1);

        $this->assertEquals($subject->name, $scholar->education[0]['subject']);
        $this->assertEquals($degree->name, $scholar->education[0]['degree']);
        $this->assertEquals($institute->name, $scholar->education[0]['institute']);
        $this->assertEquals($year, $scholar->education[0]['year']);
    }

    /** @test */
    public function scholar_has_many_documents()
    {
        $scholar = create(Scholar::class);

        $this->assertInstanceOf(HasMany::class, $scholar->documents());
        $this->assertCount(0, $scholar->documents);

        $documents = create(ScholarDocument::class, 2, ['scholar_id' => $scholar->id]);

        $this->assertCount(count($documents), $scholar->fresh()->documents);
        $this->assertEquals($documents->sortByDesc('created_at')->pluck('id'), $scholar->fresh()->documents->pluck('id'));
    }

    /** @test */
    public function progress_report_method_return_documents_of_type_progress_report()
    {
        $scholar = create(Scholar::class);

        $this->assertCount(0, $scholar->progressReports());

        $progressReports = create(ScholarDocument::class, 2, ['type' => ScholarDocumentType::PROGRESS_REPORT, 'scholar_id' => $scholar->id]);
        $otherDocuments = create(ScholarDocument::class, 1, ['type' => ScholarDocumentType::OTHER_DOCUMENT, 'scholar_id' => $scholar->id]);

        $this->assertCount(count($progressReports), $scholar->fresh()->progressReports());
        $this->assertEquals($progressReports->sortByDesc('created_at')->pluck('id'), $scholar->fresh()->progressReports()->pluck('id'));
    }

    /** @test */
    public function other_documents_method_return_documents_of_type_other_document()
    {
        $scholar = create(Scholar::class);

        $this->assertCount(0, $scholar->otherDocuments());

        $otherDocuments = create(ScholarDocument::class, 2, ['type' => ScholarDocumentType::OTHER_DOCUMENT, 'scholar_id' => $scholar->id]);
        $progressReports = create(ScholarDocument::class, 1, ['type' => ScholarDocumentType::PROGRESS_REPORT, 'scholar_id' => $scholar->id]);

        $this->assertCount(count($otherDocuments), $scholar->fresh()->otherDocuments());
        $this->assertEquals($otherDocuments->sortByDesc('created_at')->pluck('id'), $scholar->fresh()->otherDocuments()->pluck('id'));
    }
}
