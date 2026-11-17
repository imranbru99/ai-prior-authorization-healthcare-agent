<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'mrn',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'member_id',
        'phone',
        'email',
        'state',
        'notes',
        'care_coordinator_id',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function careCoordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'care_coordinator_id');
    }

    public function priorAuthRequests(): HasMany
    {
        return $this->hasMany(PriorAuthRequest::class);
    }
}
