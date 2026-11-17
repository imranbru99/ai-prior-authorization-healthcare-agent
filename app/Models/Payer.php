<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payer extends Model
{
    protected $fillable = [
        'name',
        'payer_id',
        'plan_type',
        'portal_url',
        'avg_turnaround_days',
        'supports_api',
        'is_active',
        'submission_preferences',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'supports_api' => 'boolean',
            'is_active' => 'boolean',
            'submission_preferences' => 'array',
        ];
    }

    public function priorAuthRequests(): HasMany
    {
        return $this->hasMany(PriorAuthRequest::class);
    }
}
