<?php

namespace App\Services;

use App\Models\PriorAuthRequest;
use App\Models\StatusEvent;
use App\Models\User;
use RuntimeException;

class PriorAuthWorkflowService
{
    public function __construct(private ModuleManager $modules) {}

    public function transition(PriorAuthRequest $request, string $toStatus, ?User $actor = null, ?string $note = null, bool $automated = false): PriorAuthRequest
    {
        if (! $this->modules->enabled('status_workflows') && ! $this->modules->enabled('prior_auth')) {
            throw new RuntimeException('Prior auth / status workflow modules are disabled.');
        }

        $from = $request->status;
        $request->status = $toStatus;

        if ($toStatus === 'submitted' && ! $request->submitted_at) {
            $request->submitted_at = now();
        }

        if (in_array($toStatus, ['approved', 'denied'], true)) {
            $request->decision_at = now();
        }

        $request->save();

        if ($this->modules->enabled('status_workflows')) {
            StatusEvent::query()->create([
                'prior_auth_request_id' => $request->id,
                'actor_id' => $actor?->id,
                'from_status' => $from,
                'to_status' => $toStatus,
                'event_type' => 'status_change',
                'note' => $note,
                'automated' => $automated,
                'occurred_at' => now(),
            ]);
        }

        return $request->fresh();
    }
}
