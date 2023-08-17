<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Appointment;
use App\Models\Client;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ClientAppointmentsControllerTest extends TestCase
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

    protected Client $client;
    protected Appointment $appointment;
    protected Appointment $appointmentForeign;

    function setUp(): void
    {
        parent::setUp();

        $this->appointmentForeign = Appointment::factory()->create();
        $this->client = Client::factory()->create();
        $this->appointment = Appointment::factory()->create([
            'client_id' => $this->client->id
        ]);
    }

    function tearDown(): void
    {
        $clientUser = $this->client->user;

        $this->appointment->delete();
        $this->appointmentForeign->delete();
        $this->client->delete();
        $clientUser->delete();

        parent::tearDown();
    }

    function testIndex(): void
    {
        $this->actingAs($this->client->user)
            ->getJson(route('clients.appointments.index', ['client' => $this->client->id]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(self::JSON_RESOURCE_COLLECTION_STRUCTURE);
    }

    function testShowAsClient(): void
    {
        $this->actingAs($this->client->user)
            ->get(route('clients.appointments.show', ['appointment' => $this->appointment->id, 'client' => $this->client->id]))
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_RESOURCE_STRUCTURE);

        $this->actingAs($this->client->user)
            ->get(route('clients.appointments.show', ['appointment' => $this->appointmentForeign->id, 'client' => $this->client->id]))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    function testCreateAsClient(): void
    {
        $mockAppointment = Appointment::factory()->make([
            'client_id' => $this->client->id,
        ]);
        $payload = $mockAppointment->toArray();

        $this->actingAs($this->client->user)
            ->postJson(route('clients.appointments.store', ['client' => $this->client->id]), $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(self::JSON_RESOURCE_STRUCTURE);
    }

    function testDeleteAsClient(): void
    {
        $this->actingAs($this->client->user)
            ->deleteJson(route('clients.appointments.destroy', ['appointment' => $this->appointment->id, 'client' => $this->client->id]))
            ->assertStatus(Response::HTTP_NO_CONTENT);
        $this->actingAs($this->client->user)
            ->deleteJson(route('appointments.destroy', ['appointment' => $this->appointmentForeign->id, 'client' => $this->client->id]))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
