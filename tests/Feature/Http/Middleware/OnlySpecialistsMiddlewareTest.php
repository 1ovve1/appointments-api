<?php

namespace Tests\Feature\Http\Middleware;

use App\Models\Client;
use App\Models\Specialist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class OnlySpecialistsMiddlewareTest extends TestCase
{
    public function testAccessAsClient(): void
    {
        $client = Client::factory()->create();
        $specialist = Specialist::factory()->create();

        $this->actingAs($client->user)
            ->getJson(route('specialists.appointments.index', ['specialist' => $specialist->id]))
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $client->delete();
        $specialist->delete();
    }

    public function testAccessAsSpecialist(): void
    {
        $specialist = Specialist::factory()->create();

        $this->actingAs($specialist->user)
            ->getJson(route('specialists.appointments.index', ['specialist' => $specialist->id]))
            ->assertStatus(Response::HTTP_OK);

        $specialist->delete();
    }
}
