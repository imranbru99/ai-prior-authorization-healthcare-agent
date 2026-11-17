<?php

namespace App\Services;

use App\Models\ClinicalEvidence;
use App\Models\PriorAuthRequest;
use RuntimeException;

class ClinicalEvidenceService
{
    public function __construct(private ModuleManager $modules) {}

    public function attachEvidence(PriorAuthRequest $request, array $payload): ClinicalEvidence
    {
        if (! $this->modules->enabled('clinical_evidence')) {
            throw new RuntimeException('Clinical evidence module is disabled.');
        }

        $evidence = ClinicalEvidence::query()->create(array_merge($payload, [
            'prior_auth_request_id' => $request->id,
        ]));

        $avg = (int) round($request->clinicalEvidences()->avg('relevance_score') ?? 0);
        $request->update(['evidence_score' => min(100, $avg)]);

        return $evidence;
    }
}
