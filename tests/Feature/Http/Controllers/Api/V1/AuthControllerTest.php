<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Client;
use App\Models\Specialist;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    const JSON_STRUCTURE = [
        'data' => [
            'id',
            'username',
            'email',
            'first_name',
            'last_name',
            'patronymic',
            'client',
            'specialist',
            'created_at',
            'updated_at',
            'token_access',
            'token_type',
        ],
    ];

    /**
     * A basic feature test example.
     */
    public function testLoginEmail(): void
    {
        $password = fake()->password(8);
        $user = User::factory()->create(['password' => $password]);

        $payload = [
            'email' => $user->email,
            'password' => $password
        ];

        $response = $this->post(route('user.login'), $payload);

        $response->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_STRUCTURE);

        $user->delete();
    }

    /**
     * A basic feature test example.
     */
    public function testLoginUsername(): void
    {
        $password = fake()->password(8);
        $user = User::factory()->create(['password' => $password]);

        $payload = [
            'username' => $user->username,
            'password' => $password
        ];

        $response = $this->post(route('user.login'), $payload);

        $response->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_STRUCTURE);

        $user->delete();
    }

    /**
     * A basic feature test example.
     */
    public function testEmailUsernameLogin(): void
    {
        $password = fake()->password(8);
        $user = User::factory()->create(['password' => $password]);

        $payload = [
            'email_username' => $user->username,
            'password' => $password
        ];

        $response = $this->post(route('user.login'), $payload);

        $response->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_STRUCTURE);

        $user->delete();
    }

    public function testRegisterAsClient(): void
    {
        $password = fake()->password(8);
        $payload = [
            'username' => fake()->userName(),
            'email' => fake()->email(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'patronymic' => fake()->lastName(),
            'phone' => fake()->phoneNumber(),
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->post(route('user.register.client'), $payload);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(self::JSON_STRUCTURE);

        $userId = $response->json('data.id');
        Client::where('user_id', $userId)->delete();
        User::destroy($response->json('data.id'));
    }

    public function testRegisterAsSpecialist(): void
    {
        $password = fake()->password(8);
        $payload = [
            'username' => fake()->userName(),
            'email' => fake()->email(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'patronymic' => fake()->lastName(),
            'schedule' => fake()->sentence(3, true),
            'description' => fake()->text(),
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->post(route('user.register.specialist'), $payload);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(self::JSON_STRUCTURE);

        $userId = $response->json('data.id');
        Specialist::where('user_id', $userId)->delete();
        User::destroy($userId);
    }

    public function testIndex(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('user'))
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_STRUCTURE);

        $user->delete();
    }
}
