<?php

namespace Tests\Feature\Api\V1;

use App\Models\Client;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    const JSON_STRUCTURE = [
        'id',
        'first_name',
        'last_name',
        'patronymic',
        'phone',
        'user' => [
            'login',
            'email',
            'created_at',
            'updated_at'
        ]
    ];

    const JSON_RESOURCE_STRUCTURE = [
        'data' => [
            self::JSON_STRUCTURE
        ]
    ];

    const JSON_RESOURCE_COLLECTION_STRUCTURE = [
        'data' => [
            '*' => [
                self::JSON_STRUCTURE
            ]
        ],
        'links',
        'meta'
    ];

    protected Client $client;

    function setUp(): void
    {
        parent::setUp();

        $this->client = Client::factory()->create();
    }

    function tearDown(): void
    {
        $this->client->delete();

        parent::tearDown();
    }

    function testIndex(): void
    {
        $this->get(route('clients.index'))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testShow(): void
    {
        $this->get(route('clients.show', ['client' => $this->client->id]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testShowAsClient(): void
    {
        $this->actingAs($this->client->user)
            ->get(route('clients.show', ['client' => $this->client->id]))
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_RESOURCE_STRUCTURE);
    }

    function testCreate(): void
    {
        $mockClient = Client::factory()->make();
        $payload = $mockClient->toArray();

        $this->post(route('clients.store'), $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testCreateAsClient(): void
    {
        $mockClient = Client::factory()->make();
        $payload = $mockClient->toArray();

        $this->actingAs($this->client->user)
            ->post(route('clients.store'), $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testUpdate(): void
    {
        $payload = Client::factory()->make()->toArray();

        $this->put(route('clients.update', ['client' => $this->client->id]), $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testUpdateAsUser(): void
    {
        $payload = Client::factory()->make()->toArray();

        $this->put(route('clients.update', ['client' => $this->client->id]), $payload)
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_RESOURCE_STRUCTURE);
    }

    function testDelete(): void
    {
        $this->delete(route('clients.destroy', ['client' => $this->client->id]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testDeleteAsClient(): void
    {
        $this->actingAs($this->client->user)
            ->delete(route('clients.destroy', ['client' => $this->client->id]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
