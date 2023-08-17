<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'shedule', 'description'
    ];


    /**
     * @return BelongsTo
     */
    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Check if client the same as requested user
     *
     * @param User $user
     * @return boolean
     */
    function same(User $user): bool
    {
        return $this->user_id === $user->id;
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
