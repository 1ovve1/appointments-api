<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'patronymic' => $this->patronymic,
            'client' => $this->client,
            'specialist' => $this->specialist,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'token_access' => $this->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer'
        ];
    }
}
