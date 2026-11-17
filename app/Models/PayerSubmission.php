<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayerSubmission extends Model
{
    protected $fillable = [
        'prior_auth_request_id',
        'submitted_by',
        'channel',
        'external_reference',
        'status',
        'submitted_at',
        'acknowledged_at',
        'payload_summary',
        'response_notes',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'acknowledged_at' => 'datetime',
            'payload_summary' => 'array',
        ];
    }

    public function priorAuthRequest(): BelongsTo
    {
        return $this->belongsTo(PriorAuthRequest::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function denials(): HasMany
    {
        return $this->hasMany(Denial::class);
    }
}
