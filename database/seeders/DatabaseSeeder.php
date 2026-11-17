<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\AgentRun;
use App\Models\Attachment;
use App\Models\ClinicalEvidence;
use App\Models\Denial;
use App\Models\Patient;
use App\Models\Payer;
use App\Models\PayerSubmission;
use App\Models\PriorAuthRequest;
use App\Models\StatusEvent;
use App\Models\User;
use App\Services\ModuleManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app(ModuleManager::class)->syncFromConfig();

        $users = [
            ['name' => 'Super Admin', 'email' => 'superadmin@priorauth-ai.test', 'role' => UserRole::SuperAdmin],
            ['name' => 'RCM Admin', 'email' => 'admin@priorauth-ai.test', 'role' => UserRole::Admin],
            ['name' => 'UM Manager', 'email' => 'manager@priorauth-ai.test', 'role' => UserRole::Manager],
            ['name' => 'Auth Specialist', 'email' => 'staff@priorauth-ai.test', 'role' => UserRole::Staff],
            ['name' => 'Provider Client', 'email' => 'client@priorauth-ai.test', 'role' => UserRole::Client],
            ['name' => 'Demo User', 'email' => 'demo@priorauth-ai.test', 'role' => UserRole::Demo],
        ];

        $created = [];
        foreach ($users as $data) {
            $created[$data['role']->value] = User::query()->updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => $data['role']->value,
                    'phone' => '+1-555-0400',
                    'organization_name' => 'Northwind Health System',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }

        $staff = $created[UserRole::Staff->value];
        $demo = $created[UserRole::Demo->value];

        $payers = collect([
            ['name' => 'Aetna Commercial', 'payer_id' => 'AET-COM', 'plan_type' => 'commercial', 'avg_turnaround_days' => 5, 'supports_api' => true],
            ['name' => 'UnitedHealthcare MA', 'payer_id' => 'UHC-MA', 'plan_type' => 'medicare_advantage', 'avg_turnaround_days' => 7, 'supports_api' => true],
            ['name' => 'State Medicaid MCO', 'payer_id' => 'MCD-01', 'plan_type' => 'medicaid', 'avg_turnaround_days' => 10, 'supports_api' => false],
            ['name' => 'Cigna PPO', 'payer_id' => 'CIG-PPO', 'plan_type' => 'commercial', 'avg_turnaround_days' => 6, 'supports_api' => false],
        ])->map(fn (array $data) => Payer::query()->updateOrCreate(
            ['payer_id' => $data['payer_id']],
            array_merge($data, [
                'portal_url' => 'https://payer.example/portal/'.$data['payer_id'],
                'is_active' => true,
                'submission_preferences' => ['preferred' => $data['supports_api'] ? 'api' : 'portal'],
            ])
        ));

        $patients = collect([
            ['mrn' => 'MRN-9001', 'first_name' => 'Elena', 'last_name' => 'Vasquez', 'member_id' => 'MEM-9001', 'state' => 'TX'],
            ['mrn' => 'MRN-9002', 'first_name' => 'Marcus', 'last_name' => 'Chen', 'member_id' => 'MEM-9002', 'state' => 'CA'],
            ['mrn' => 'MRN-9003', 'first_name' => 'Priya', 'last_name' => 'Shah', 'member_id' => 'MEM-9003', 'state' => 'NY'],
            ['mrn' => 'MRN-9004', 'first_name' => 'Jordan', 'last_name' => 'Blake', 'member_id' => 'MEM-9004', 'state' => 'FL'],
            ['mrn' => 'MRN-9005', 'first_name' => 'Aisha', 'last_name' => 'Okoro', 'member_id' => 'MEM-9005', 'state' => 'IL'],
        ])->map(function (array $data) use ($staff) {
            return Patient::query()->updateOrCreate(
                ['mrn' => $data['mrn']],
                array_merge($data, [
                    'date_of_birth' => now()->subYears(rand(28, 72))->toDateString(),
                    'gender' => collect(['female', 'male', 'other'])->random(),
                    'phone' => '+1-555-3'.rand(100, 999),
                    'email' => strtolower($data['first_name']).'.demo@example.com',
                    'care_coordinator_id' => $staff->id,
                    'notes' => 'Seeded prior-auth demo patient.',
                ])
            );
        });

        $services = [
            ['code' => '72148', 'dx' => 'M54.5', 'desc' => 'MRI lumbar spine without contrast'],
            ['code' => '27447', 'dx' => 'M17.11', 'desc' => 'Total knee arthroplasty'],
            ['code' => '93306', 'dx' => 'I50.9', 'desc' => 'Echocardiogram complete'],
            ['code' => 'J1745', 'dx' => 'K50.90', 'desc' => 'Infliximab infusion'],
            ['code' => '64635', 'dx' => 'M54.16', 'desc' => 'RF ablation lumbar facet'],
        ];

        foreach ($patients as $index => $patient) {
            $service = $services[$index];
            $payer = $payers[$index % $payers->count()];
            $status = collect(['evidence_gathering', 'ready', 'submitted', 'pending', 'denied'])->get($index);

            $request = PriorAuthRequest::query()->updateOrCreate(
                ['case_number' => 'PA-2026-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT)],
                [
                    'patient_id' => $patient->id,
                    'payer_id' => $payer->id,
                    'assigned_to' => $staff->id,
                    'service_code' => $service['code'],
                    'diagnosis_codes' => $service['dx'],
                    'service_description' => $service['desc'],
                    'urgency' => $index === 1 ? 'urgent' : 'routine',
                    'status' => $status,
                    'requested_service_date' => now()->addDays(14 + $index),
                    'due_date' => now()->addDays(3 + $index),
                    'evidence_score' => 55 + ($index * 8),
                    'ai_assisted' => $index < 3,
                    'clinical_summary' => 'Clinical indications documented for '.$service['desc'].'.',
                    'notes' => 'Demo prior authorization case.',
                    'submitted_at' => in_array($status, ['submitted', 'pending', 'denied'], true) ? now()->subDays(2) : null,
                    'decision_at' => $status === 'denied' ? now()->subDay() : null,
                ]
            );

            ClinicalEvidence::query()->updateOrCreate(
                [
                    'prior_auth_request_id' => $request->id,
                    'title' => 'Supporting note for '.$service['code'],
                ],
                [
                    'evidence_type' => 'progress_note',
                    'source' => 'ehr',
                    'summary' => 'Failed conservative therapy documented; imaging correlates with symptoms.',
                    'guideline_reference' => $payer->name.' medical policy',
                    'relevance_score' => 70 + $index * 5,
                    'ai_extracted' => $index < 3,
                    'status' => 'reviewed',
                    'metadata' => ['seed' => true],
                ]
            );

            StatusEvent::query()->updateOrCreate(
                [
                    'prior_auth_request_id' => $request->id,
                    'to_status' => $status,
                    'event_type' => 'status_change',
                ],
                [
                    'actor_id' => $demo->id,
                    'from_status' => 'draft',
                    'note' => 'Seeded workflow transition',
                    'automated' => false,
                    'occurred_at' => now()->subHours(6 + $index),
                ]
            );

            Attachment::query()->updateOrCreate(
                [
                    'prior_auth_request_id' => $request->id,
                    'file_name' => 'lmn-'.$request->case_number.'.pdf',
                ],
                [
                    'uploaded_by' => $staff->id,
                    'label' => 'Letter of medical necessity',
                    'mime_type' => 'application/pdf',
                    'file_size_kb' => 220 + $index * 15,
                    'category' => 'lmn',
                    'storage_path' => 'attachments/demo/lmn-'.$request->case_number.'.pdf',
                    'included_in_packet' => true,
                ]
            );

            if (in_array($status, ['submitted', 'pending', 'denied'], true)) {
                $submission = PayerSubmission::query()->updateOrCreate(
                    [
                        'prior_auth_request_id' => $request->id,
                        'external_reference' => 'EXT-'.$request->case_number,
                    ],
                    [
                        'submitted_by' => $staff->id,
                        'channel' => $payer->supports_api ? 'api' : 'portal',
                        'status' => $status === 'denied' ? 'acknowledged' : 'submitted',
                        'submitted_at' => now()->subDays(2),
                        'acknowledged_at' => $status === 'denied' ? now()->subDay() : null,
                        'payload_summary' => [
                            'service_code' => $service['code'],
                            'evidence_score' => $request->evidence_score,
                        ],
                        'response_notes' => $status === 'denied' ? 'Medical necessity not established.' : null,
                    ]
                );

                if ($status === 'denied') {
                    Denial::query()->updateOrCreate(
                        [
                            'prior_auth_request_id' => $request->id,
                            'denial_code' => 'MN-01',
                        ],
                        [
                            'payer_submission_id' => $submission->id,
                            'reason' => 'Medical necessity criteria not met',
                            'status' => 'open',
                            'appeal_deadline' => now()->addDays(30),
                            'turnaround_hours' => 36,
                            'appeal_notes' => null,
                        ]
                    );
                }
            }

            if ($index < 3) {
                AgentRun::query()->updateOrCreate(
                    [
                        'prior_auth_request_id' => $request->id,
                        'run_type' => 'evidence_gather',
                    ],
                    [
                        'triggered_by' => $demo->id,
                        'status' => 'completed',
                        'steps' => 4,
                        'actions' => [
                            ['step' => 1, 'action' => 'Parse codes'],
                            ['step' => 2, 'action' => 'Mine EHR'],
                            ['step' => 3, 'action' => 'Map policy'],
                            ['step' => 4, 'action' => 'Score packet'],
                        ],
                        'recommendation' => 'Submit with LMN + imaging report.',
                        'started_at' => now()->subHours(3),
                        'finished_at' => now()->subHours(2),
                    ]
                );
            }
        }
    }
}
