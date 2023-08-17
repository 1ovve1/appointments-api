<?php

namespace Tests\Feature\Api\V1;

use App\Models\Client;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    const JSON_STRUCTURE = [
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

    protected Client $client;

    function setUp(): void
    {
        parent::setUp();

        $this->client = Client::factory()->create();
    }

    function tearDown(): void
    {
        $clientUser = $this->client->user;

        $this->client->delete();
        $clientUser->delete();

        parent::tearDown();
    }

    function testIndex(): void
    {
        $this->getJson(route('clients.index'))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testIndexAsClient(): void
    {
        $this->actingAs($this->client->user)
            ->getJson(route('clients.index'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    function testShow(): void
    {
        $this->getJson(route('clients.show', ['client' => $this->client->id]))
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

        $this->postJson(route('clients.store'), $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testCreateAsClient(): void
    {
        $mockClient = Client::factory()->make();
        $payload = $mockClient->toArray();

        $this->actingAs($this->client->user)
            ->postJson(route('clients.store'), $payload)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    function testUpdate(): void
    {
        $payload = Client::factory()->make()->toArray();

        $this->putJson(route('clients.update', ['client' => $this->client->id]), $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testUpdateAsClient(): void
    {
        $payload = Client::factory()->make([
            'id' => $this->client->id,
            'user_id' => $this->client->user->id,
        ])->toArray();

        $this->actingAs($this->client->user)
            ->putJson(route('clients.update', ['client' => $this->client->id]), $payload)
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_RESOURCE_STRUCTURE);
    }

    function testDelete(): void
    {
        $this->deleteJson(route('clients.destroy', ['client' => $this->client->id]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testDeleteAsClient(): void
    {
        $this->actingAs($this->client->user)
            ->deleteJson(route('clients.destroy', ['client' => $this->client->id]))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
