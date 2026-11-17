<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalEvidence extends Model
{
    protected $table = 'clinical_evidences';

    protected $fillable = [
        'prior_auth_request_id',
        'evidence_type',
        'source',
        'title',
        'summary',
        'guideline_reference',
        'relevance_score',
        'ai_extracted',
        'status',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'ai_extracted' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public function priorAuthRequest(): BelongsTo
    {
        return $this->belongsTo(PriorAuthRequest::class);
    }
}
