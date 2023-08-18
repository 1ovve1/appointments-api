<?php

namespace Tests\Feature\Http\Middleware;

use App\Models\Client;
use App\Models\Specialist;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class OnlyClientsMiddlewareTest extends TestCase
{
    public function testAccessAsClient(): void
    {
        $client = Client::factory()->create();

        $this->actingAs($client->user)
            ->getJson(route('clients.appointments.index', ['client' => $client->id]))
            ->assertStatus(Response::HTTP_OK);

        $client->delete();
    }

    public function testAccessAsSpecialist(): void
    {
        $client = Client::factory()->create();
        $specialist = Specialist::factory()->create();

        $this->actingAs($specialist->user)
            ->getJson(route('clients.appointments.index', ['client' => $client->id]))
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $client->delete();
        $specialist->delete();
    }
}
