<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriorAuthRequest extends Model
{
    protected $fillable = [
        'case_number',
        'patient_id',
        'payer_id',
        'assigned_to',
        'service_code',
        'diagnosis_codes',
        'service_description',
        'urgency',
        'status',
        'requested_service_date',
        'due_date',
        'evidence_score',
        'ai_assisted',
        'clinical_summary',
        'notes',
        'submitted_at',
        'decision_at',
    ];

    protected function casts(): array
    {
        return [
            'requested_service_date' => 'date',
            'due_date' => 'date',
            'ai_assisted' => 'boolean',
            'submitted_at' => 'datetime',
            'decision_at' => 'datetime',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function payer(): BelongsTo
    {
        return $this->belongsTo(Payer::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function clinicalEvidences(): HasMany
    {
        return $this->hasMany(ClinicalEvidence::class);
    }

    public function payerSubmissions(): HasMany
    {
        return $this->hasMany(PayerSubmission::class);
    }

    public function denials(): HasMany
    {
        return $this->hasMany(Denial::class);
    }

    public function statusEvents(): HasMany
    {
        return $this->hasMany(StatusEvent::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function agentRuns(): HasMany
    {
        return $this->hasMany(AgentRun::class);
    }
}
