<?php

namespace Tests\Unit;

use App\ExternalAuthority;
use App\Models\College;
use App\Models\Cosupervisor;
use App\Models\ScholarCosupervisor;
use App\Models\Teacher;
use App\Models\User;
use App\Types\TeacherStatus;
use App\Types\UserCategory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScholarCosupervisorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function schoalr_cosupervisor_is_related_to_a_user_cosupervisor()
    {
        $faculty = factory(User::class)->states('cosupervisor')->create();
        $facultyCosupervisor = create(ScholarCosupervisor::class, 1, [
            'person_type' => User::class,
            'person_id' => $faculty->id,
        ]);
        $this->assertInstanceOf(MorphTo::class, $facultyCosupervisor->person());

        $this->assertTrue($faculty->is($facultyCosupervisor->person));
    }

    /** @test */
    public function cosupervisor_details_for_existing_professor_can_be_accessed_as_direct_properties()
    {
        $faculty = factory(User::class)->states('cosupervisor')->create();
        $facultyCosupervisor = create(ScholarCosupervisor::class, 1, [
            'person_type' => User::class,
            'person_id' => $faculty->id,
        ]);

        $this->assertEquals($facultyCosupervisor->person->name, $facultyCosupervisor->name);
        $this->assertEquals($facultyCosupervisor->person->email, $facultyCosupervisor->email);
        $this->assertEquals($facultyCosupervisor->person->designation, $facultyCosupervisor->designation);
        $this->assertEquals($facultyCosupervisor->person->college->name, $facultyCosupervisor->affiliation);

        $external = factory(ExternalAuthority::class)->states('cosupervisor')->create();
        $externalCosupervisor = create(ScholarCosupervisor::class, 1, [
            'person_type' => User::class,
            'person_id' => $external->id,
        ]);

        $this->assertEquals($externalCosupervisor->person->affiliation, $externalCosupervisor->affiliation);
    }
}
