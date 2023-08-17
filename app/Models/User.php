<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * @return HasOne
     */
    function client(): HasOne
    {
        return $this->hasOne(Client::class, 'user_id', 'id');
    }

    /**
     * @return HasOne
     */
    function specialist(): HasOne
    {
        return $this->hasOne(Specialist::class, 'user_id', 'id');
    }

    /**
     * TODO: admins currently not supported
     *
     * @return boolean
     */
    function isAdmin(): bool
    {
        return false;
    }

    /**
     * @return boolean
     */
    function isSpecialist(): bool
    {
        return $this->specialist !== null;
    }

    /**
     * @return boolean
     */
    function isClient(): bool
    {
        return $this->client !== null;
    }

    /**
     * @param Appointment $appointment
     * @return boolean
     */
    function hasAppointment(Appointment $appointment): bool
    {
        return $this->appointments?->contains(fn($model) => $model->id === $appointment->id) ?? false;
    }
}
