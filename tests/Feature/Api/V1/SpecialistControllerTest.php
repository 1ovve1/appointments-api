<?php

namespace Tests\Feature\Api\V1;

use App\Models\Specialist;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SpecialistControllerTest extends TestCase
{
    const JSON_STRUCTURE = [
        'id',
        'shedule',
        'description',
        'user' => [
            'login',
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

    protected Specialist $specialist;

    function setUp(): void
    {
        parent::setUp();

        $this->specialist = Specialist::factory()->create();
    }

    function tearDown(): void
    {
        $this->specialist->delete();

        parent::tearDown();
    }

    function testIndex(): void
    {
        $this->getJson(route('specialists.index'))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testIndexAsSpecialist(): void
    {
        $this->actingAs($this->specialist->user)
            ->getJson(route('specialists.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(self::JSON_RESOURCE_COLLECTION_STRUCTURE);
    }

    function testShow(): void
    {
        $this->getJson(route('specialists.show', ['specialist' => $this->specialist->id]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testShowAsSpecialist(): void
    {
        $this->actingAs($this->specialist->user)
            ->get(route('specialists.show', ['specialist' => $this->specialist->id]))
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_RESOURCE_STRUCTURE);
    }

    function testCreate(): void
    {
        $mockSpecialist = Specialist::factory()->make();
        $payload = $mockSpecialist->toArray();

        $this->postJson(route('specialists.store'), $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testCreateAsSpecialist(): void
    {
        $mockSpecialist = Specialist::factory()->make();
        $payload = $mockSpecialist->toArray();

        $this->actingAs($this->specialist->user)
            ->postJson(route('specialists.store'), $payload)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    function testUpdate(): void
    {
        $payload = Specialist::factory()->make()->toArray();

        $this->putJson(route('specialists.update', ['specialist' => $this->specialist->id]), $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testUpdateAsSpecialist(): void
    {
        $payload = Specialist::factory()->make([
            'id' => $this->specialist->id,
            'user_id' => $this->specialist->user->id,
        ])->toArray();

        $this->actingAs($this->specialist->user)
            ->putJson(route('specialists.update', ['specialist' => $this->specialist->id]), $payload)
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_RESOURCE_STRUCTURE);
    }

    function testDelete(): void
    {
        $this->deleteJson(route('specialists.destroy', ['specialist' => $this->specialist->id]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testDeleteAsSpecialist(): void
    {
        $this->actingAs($this->specialist->user)
            ->deleteJson(route('specialists.destroy', ['specialist' => $this->specialist->id]))
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
