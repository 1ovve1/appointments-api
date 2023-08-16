<?php

namespace Tests\Feature\Api\V1;

use App\Models\Specialist;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SpecialistControllerTest extends TestCase
{
    const JSON_STRUCTURE = [
        'id',
        'first_name',
        'last_name',
        'patronymic',
        'shedule',
        'description',
        'user' => [
            'login',
            'email',
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
        $this->getJson(route('specialist.index'))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testIndexAsClient(): void
    {
        $this->actingAs($this->specialist->user)
            ->getJson(route('specialist.index'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    function testShow(): void
    {
        $this->getJson(route('specialist.show', ['client' => $this->specialist->id]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testShowAsClient(): void
    {
        $this->actingAs($this->specialist->user)
            ->get(route('specialist.show', ['client' => $this->specialist->id]))
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_RESOURCE_STRUCTURE);
    }

    function testCreate(): void
    {
        $mockSpecialist = Specialist::factory()->make();
        $payload = $mockSpecialist->toArray();

        $this->postJson(route('specialist.store'), $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testCreateAsClient(): void
    {
        $mockSpecialist = Specialist::factory()->make();
        $payload = $mockSpecialist->toArray();

        $this->actingAs($this->specialist->user)
            ->postJson(route('specialist.store'), $payload)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    function testUpdate(): void
    {
        $payload = Specialist::factory()->make()->toArray();

        $this->putJson(route('specialist.update', ['client' => $this->specialist->id]), $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testUpdateAsClent(): void
    {
        $payload = Specialist::factory()->make([
            'id' => $this->specialist->id,
            'user_id' => $this->specialist->user->id,
        ])->toArray();

        $this->actingAs($this->specialist->user)
            ->putJson(route('specialist.update', ['client' => $this->specialist->id]), $payload)
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_RESOURCE_STRUCTURE);
    }

    function testDelete(): void
    {
        $this->deleteJson(route('specialist.destroy', ['client' => $this->specialist->id]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    function testDeleteAsClient(): void
    {
        $this->actingAs($this->specialist->user)
            ->deleteJson(route('specialist.destroy', ['client' => $this->specialist->id]))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
