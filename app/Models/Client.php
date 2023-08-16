<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'patronymic', 'phone'
    ];


    /**
     * @return BelongsTo
     */
    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
}
