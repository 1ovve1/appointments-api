<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecialistResource extends JsonResource
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
            'shedule' => $this->shedule,
            'description' => $this->description,
            'user' => [
                'login' => $this->user->login,
                'email' => $this->user->email,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'patronymic' => $this->user->patronymic,
                'created_at' => $this->user->created_at,
                'updated_at' => $this->user->updated_at
            ],
        ];
    }
}
