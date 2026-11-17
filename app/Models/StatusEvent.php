<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusEvent extends Model
{
    protected $fillable = [
        'prior_auth_request_id',
        'actor_id',
        'from_status',
        'to_status',
        'event_type',
        'note',
        'automated',
        'occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'automated' => 'boolean',
            'occurred_at' => 'datetime',
        ];
    }

    public function priorAuthRequest(): BelongsTo
    {
        return $this->belongsTo(PriorAuthRequest::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
