<?php

namespace App\Services;

use App\Models\PayerSubmission;
use App\Models\PriorAuthRequest;
use App\Models\User;
use RuntimeException;

class PayerSubmissionService
{
    public function __construct(
        private ModuleManager $modules,
        private PriorAuthWorkflowService $workflow,
    ) {}

    public function submit(PriorAuthRequest $request, User $actor, string $channel = 'portal'): PayerSubmission
    {
        if (! $this->modules->enabled('payer_submissions')) {
            throw new RuntimeException('Payer submissions module is disabled.');
        }

        $submission = PayerSubmission::query()->create([
            'prior_auth_request_id' => $request->id,
            'submitted_by' => $actor->id,
            'channel' => $channel,
            'external_reference' => 'PA-'.strtoupper(substr(md5((string) $request->id.microtime()), 0, 10)),
            'status' => 'submitted',
            'submitted_at' => now(),
            'payload_summary' => [
                'service_code' => $request->service_code,
                'diagnosis_codes' => $request->diagnosis_codes,
                'evidence_score' => $request->evidence_score,
            ],
        ]);

        $this->workflow->transition($request, 'submitted', $actor, 'Submitted via '.$channel, automated: false);

        return $submission;
    }
}
