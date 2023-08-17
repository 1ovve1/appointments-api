<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Specialist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $clientsId = Client::all()->pluck('id')->toArray();
        $specialistsId = Specialist::all()->pluck('id')->toArray();

        return [
            'specialist_id' => fake()->randomElement($specialistsId),
            'client_id' => fake()->randomElement($clientsId),
        ];
    }
}
