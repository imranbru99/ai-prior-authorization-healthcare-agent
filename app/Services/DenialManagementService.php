<?php

namespace App\Services;

use App\Models\Denial;
use App\Models\PriorAuthRequest;
use RuntimeException;

class DenialManagementService
{
    public function __construct(
        private ModuleManager $modules,
        private PriorAuthWorkflowService $workflow,
    ) {}

    public function recordDenial(PriorAuthRequest $request, array $payload): Denial
    {
        if (! $this->modules->enabled('denial_tracking')) {
            throw new RuntimeException('Denial tracking module is disabled.');
        }

        $denial = Denial::query()->create(array_merge($payload, [
            'prior_auth_request_id' => $request->id,
            'status' => $payload['status'] ?? 'open',
        ]));

        $this->workflow->transition($request, 'denied', null, $denial->reason, automated: true);

        return $denial;
    }

    public function markAppealed(Denial $denial, ?string $notes = null): Denial
    {
        $denial->update([
            'status' => 'appealed',
            'appealed_at' => now(),
            'appeal_notes' => $notes,
        ]);

        return $denial->fresh();
    }
}
