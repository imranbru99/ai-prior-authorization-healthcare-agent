<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Denial extends Model
{
    protected $fillable = [
        'prior_auth_request_id',
        'payer_submission_id',
        'denial_code',
        'reason',
        'status',
        'appeal_deadline',
        'appealed_at',
        'resolved_at',
        'outcome',
        'turnaround_hours',
        'appeal_notes',
    ];

    protected function casts(): array
    {
        return [
            'appeal_deadline' => 'date',
            'appealed_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    public function priorAuthRequest(): BelongsTo
    {
        return $this->belongsTo(PriorAuthRequest::class);
    }

    public function payerSubmission(): BelongsTo
    {
        return $this->belongsTo(PayerSubmission::class);
    }
}
