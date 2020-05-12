<?php

namespace Tests\Unit;

use App\ExternalAuthority;
use App\Models\College;
use App\Models\Cosupervisor;
use App\Models\Teacher;
use App\Models\User;
use App\Types\TeacherStatus;
use App\Types\UserCategory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CosupervisorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cosupervisor_is_related_to_an_existing_user()
    {
        $faculty = create(User::class, ['category' => UserCategory::FACULTY_TEACHER]);
        $cosupervisor = create(Cosupervisor::class, 1, [
            'person_type' => User::class,
            'person_id' => $faculty->id,
        ]);

        $this->assertInstanceOf(MorphTo::class, $cosupervisor->person());
        $this->assertTrue($faculty->is($cosupervisor->person));
    }

    /** @test */
    public function cosupervisor_details_for_existing_professor_can_be_accessed_as_direct_properties()
    {
        $cosupervisor = create(Cosupervisor::class, 1, [
            'person_type' => User::class,
            'person_id' => create(User::class)->id,
        ]);

        $this->assertEquals($cosupervisor->person->name, $cosupervisor->name);
        $this->assertEquals($cosupervisor->person->email, $cosupervisor->email);
        $this->assertEquals($cosupervisor->person->designation, $cosupervisor->designation);
        $this->assertEquals($cosupervisor->person->college->name, $cosupervisor->affiliation);

        $anotherCosupervisor = create(Cosupervisor::class, 1, [
            'person_type' => ExternalAuthority::class,
            'person_id' => create(ExternalAuthority::class)->id,
        ]);
        $this->assertEquals($anotherCosupervisor->person->affiliation, $anotherCosupervisor->affiliation);
    }
}
