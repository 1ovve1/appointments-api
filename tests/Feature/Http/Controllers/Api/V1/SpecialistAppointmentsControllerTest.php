<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Appointment;
use App\Models\Specialist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SpecialistAppointmentsControllerTest extends TestCase
{
    const JSON_STRUCTURE = AppointmentControllerTest::JSON_STRUCTURE;

    const JSON_RESOURCE_STRUCTURE = [
        'data' =>
            self::JSON_STRUCTURE
    ];

    const JSON_RESOURCE_COLLECTION_STRUCTURE = [
        'data' => [
            '*' =>
                self::JSON_STRUCTURE
        ],
        'links',
        'meta'
    ];

    protected Specialist $specialist;
    protected Appointment $appointment;
    protected Appointment $appointmentForeign;

    function setUp(): void
    {
        parent::setUp();

        $this->appointmentForeign = Appointment::factory()->create();
        $this->specialist = Specialist::factory()->create();
        $this->appointment = Appointment::factory()->create([
            'specialist_id' => $this->specialist->id
        ]);
    }

    function tearDown(): void
    {
        $specialistUser = $this->specialist->user;

        $this->appointment->delete();
        $this->appointmentForeign->delete();
        $this->specialist->delete();
        $specialistUser->delete();

        parent::tearDown();
    }

    function testIndex(): void
    {
        $this->actingAs($this->specialist->user)
            ->getJson(route('specialists.appointments.index', ['specialist' => $this->specialist->id]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(self::JSON_RESOURCE_COLLECTION_STRUCTURE);
    }

    function testShowAsClient(): void
    {
        $this->actingAs($this->specialist->user)
            ->get(route('specialists.appointments.show', ['appointment' => $this->appointment->id, 'specialist' => $this->specialist->id]))
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_RESOURCE_STRUCTURE);

        $this->actingAs($this->specialist->user)
            ->get(route('specialists.appointments.show', ['appointment' => $this->appointmentForeign->id, 'specialist' => $this->specialist->id]))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    function testDeleteAsClient(): void
    {
        $this->actingAs($this->specialist->user)
            ->deleteJson(route('specialists.appointments.destroy', ['appointment' => $this->appointment->id, 'specialist' => $this->specialist->id]))
            ->assertStatus(Response::HTTP_NO_CONTENT);
        $this->actingAs($this->specialist->user)
            ->deleteJson(route('specialists.appointments.destroy', ['appointment' => $this->appointmentForeign->id, 'specialist' => $this->specialist->id]))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
