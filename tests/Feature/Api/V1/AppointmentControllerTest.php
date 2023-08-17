<?php

namespace Tests\Feature\Api\V1;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Specialist;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AppointmentControllerTest extends TestCase
{
    const JSON_STRUCTURE = [
        'id',
        'client' => [
            'id',
            'phone',
            'user' => [
                'username',
                'email',
                'first_name',
                'last_name',
                'patronymic',
                'created_at',
                'updated_at'
            ]
        ],
        'specialist' => [
            'id',
            'shedule',
            'description',
            'user' => [
                'username',
                'email',
                'first_name',
                'last_name',
                'patronymic',
                'created_at',
                'updated_at'
            ]
        ],
    ];

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
    protected Client $client;
    protected Appointment $appointment;
    protected Appointment $appointmentForeign;

    function setUp(): void
    {
        parent::setUp();

        $this->appointmentForeign = Appointment::factory()->create();
        $this->specialist = Specialist::factory()->create();
        $this->client = Client::factory()->create();
        $this->appointment = Appointment::factory()->create([
            'specialist_id' => $this->specialist->id,
            'client_id' => $this->client->id
        ]);
    }

    function tearDown(): void
    {
        $specialistUser = $this->specialist->user;
        $clientUser = $this->client->user;

        $this->appointment->delete();
        $this->appointmentForeign->delete();
        $this->specialist->delete();
        $this->client->delete();
        $specialistUser->delete();
        $clientUser->delete();

        parent::tearDown();
    }

    function testIndex(): void
    {
        $this->getJson(route('appointments.index'))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testIndexAsSpecialist(): void
    {
        $this->actingAs($this->specialist->user)
            ->getJson(route('appointments.index'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    function testIndexAsClient(): void
    {
        $this->actingAs($this->client->user)
            ->getJson(route('appointments.index'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    function testShow(): void
    {
        $this->getJson(route('appointments.show', ['appointment' => $this->appointment->id]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

            $this->getJson(route('appointments.show', ['appointment' => $this->appointmentForeign->id]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testShowAsSpecialist(): void
    {
        $this->actingAs($this->specialist->user)
            ->get(route('appointments.show', ['appointment' => $this->appointment->id]))
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_RESOURCE_STRUCTURE);

        $this->actingAs($this->specialist->user)
            ->get(route('appointments.show', ['appointment' => $this->appointmentForeign->id]))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }


    function testShowAsClient(): void
    {
        $this->actingAs($this->client->user)
            ->get(route('appointments.show', ['appointment' => $this->appointment->id]))
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_RESOURCE_STRUCTURE);

        $this->actingAs($this->client->user)
            ->get(route('appointments.show', ['appointment' => $this->appointmentForeign->id]))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    function testCreate(): void
    {
        $mockAppointment = Appointment::factory()->make();
        $payload = $mockAppointment->toArray();

        $this->postJson(route('appointments.store'), $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testCreateAsSpecialist(): void
    {
        $mockAppointment = Appointment::factory()->make([
            'client_id' => $this->specialist->id
        ]);
        $payload = $mockAppointment->toArray();

        $this->actingAs($this->specialist->user)
            ->postJson(route('appointments.store'), $payload)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    function testCreateAsClient(): void
    {
        $mockAppointment = Appointment::factory()->make([
            'client_id' => $this->client->id,
        ]);
        $payload = $mockAppointment->toArray();

        $this->actingAs($this->client->user)
            ->postJson(route('appointments.store'), $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(self::JSON_RESOURCE_STRUCTURE);
    }
    /**
     * @return HasOne
     */
    function client(): HasOne
    {
        return $this->hasOne(Client::class, 'user_id', 'id');
    }
    function testUpdate(): void
    {
        $payload = Appointment::factory()->make()->toArray();

        $this->putJson(route('appointments.update', ['appointment' => $this->appointment->id]), $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->putJson(route('appointments.update', ['appointment' => $this->appointmentForeign->id]), $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testUpdateAsSpecialist(): void
    {
        $payload = Appointment::factory()->make([
            'specialist_id' => $this->specialist->id,
        ])->toArray();

        $this->actingAs($this->specialist->user)
            ->putJson(route('appointments.update', ['appointment' => $this->appointment->id]), $payload)
            ->assertStatus(Response::HTTP_FORBIDDEN);
        $this->actingAs($this->specialist->user)
            ->putJson(route('appointments.update', ['appointment' => $this->appointmentForeign->id]), $payload)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    function testUpdateAsClient(): void
    {
        $payload = Appointment::factory()->make([
            'client_id' => $this->client->id,
        ])->toArray();

        $this->actingAs($this->client->user)
            ->putJson(route('appointments.update', ['appointment' => $this->appointment->id]), $payload)
            ->assertStatus(Response::HTTP_FORBIDDEN);
        $this->actingAs($this->client->user)
            ->putJson(route('appointments.update', ['appointment' => $this->appointmentForeign->id]), $payload)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    function testDelete(): void
    {
        $this->deleteJson(route('appointments.destroy', ['appointment' => $this->appointmentForeign->id]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->deleteJson(route('appointments.destroy', ['appointment' => $this->appointmentForeign->id]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testDeleteAsSpecialist(): void
    {
        $this->actingAs($this->specialist->user)
            ->deleteJson(route('appointments.destroy', ['appointment' => $this->appointment->id]))
            ->assertStatus(Response::HTTP_FORBIDDEN);
            $this->actingAs($this->specialist->user)
            ->deleteJson(route('appointments.destroy', ['appointment' => $this->appointmentForeign->id]))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    function testDeleteAsClient(): void
    {
        $this->actingAs($this->client->user)
            ->deleteJson(route('appointments.destroy', ['appointment' => $this->appointment->id]))
            ->assertStatus(Response::HTTP_NO_CONTENT);
        $this->actingAs($this->client->user)
            ->deleteJson(route('appointments.destroy', ['appointment' => $this->appointmentForeign->id]))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
