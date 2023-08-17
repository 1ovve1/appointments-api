<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client' => [
                'id' => $this->client->id,
                'phone' => $this->client->phone,
                'user' => [
                    'username' => $this->client->user->username,
                    'email' => $this->client->user->email,
                    'first_name' => $this->client->user->first_name,
                    'last_name' => $this->client->user->last_name,
                    'patronymic' => $this->client->user->patronymic,
                    'created_at' => $this->client->user->created_at,
                    'updated_at' => $this->client->user->updated_at
                ]
            ],
            'specialist' => [
                'id' => $this->specialist->id,
                'schedule' => $this->specialist->sсhedule,
                'description' => $this->specialist->description,
                'user' => [
                    'username' => $this->client->user->username,
                    'email' => $this->specialist->user->email,
                    'first_name' => $this->specialist->user->first_name,
                    'last_name' => $this->specialist->user->last_name,
                    'patronymic' => $this->specialist->user->patronymic,
                    'created_at' => $this->specialist->user->created_at,
                    'updated_at' => $this->specialist->user->updated_at,
                ]
            ],
        ];
    }
}
