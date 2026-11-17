<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentRun extends Model
{
    protected $fillable = [
        'prior_auth_request_id',
        'triggered_by',
        'run_type',
        'status',
        'steps',
        'actions',
        'recommendation',
        'started_at',
        'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'actions' => 'array',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function priorAuthRequest(): BelongsTo
    {
        return $this->belongsTo(PriorAuthRequest::class);
    }

    public function triggerer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }
}
