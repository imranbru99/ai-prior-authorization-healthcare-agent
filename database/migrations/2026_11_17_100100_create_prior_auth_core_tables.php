<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('mrn')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('member_id')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('state')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('care_coordinator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('payers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('payer_id')->unique();
            $table->string('plan_type')->default('commercial');
            $table->string('portal_url')->nullable();
            $table->unsignedInteger('avg_turnaround_days')->default(7);
            $table->boolean('supports_api')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('submission_preferences')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('prior_auth_requests', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('service_code');
            $table->string('diagnosis_codes')->nullable();
            $table->string('service_description');
            $table->string('urgency')->default('routine');
            $table->string('status')->default('draft');
            $table->date('requested_service_date')->nullable();
            $table->date('due_date')->nullable();
            $table->unsignedTinyInteger('evidence_score')->default(0);
            $table->boolean('ai_assisted')->default(false);
            $table->text('clinical_summary')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('decision_at')->nullable();
            $table->timestamps();
        });

        Schema::create('clinical_evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prior_auth_request_id')->constrained()->cascadeOnDelete();
            $table->string('evidence_type');
            $table->string('source')->default('ehr');
            $table->string('title');
            $table->text('summary')->nullable();
            $table->string('guideline_reference')->nullable();
            $table->unsignedTinyInteger('relevance_score')->default(50);
            $table->boolean('ai_extracted')->default(false);
            $table->string('status')->default('collected');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('payer_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prior_auth_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('channel')->default('portal');
            $table->string('external_reference')->nullable();
            $table->string('status')->default('queued');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->json('payload_summary')->nullable();
            $table->text('response_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('denials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prior_auth_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payer_submission_id')->nullable()->constrained()->nullOnDelete();
            $table->string('denial_code')->nullable();
            $table->string('reason');
            $table->string('status')->default('open');
            $table->date('appeal_deadline')->nullable();
            $table->timestamp('appealed_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->string('outcome')->nullable();
            $table->unsignedInteger('turnaround_hours')->nullable();
            $table->text('appeal_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('status_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prior_auth_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->string('event_type')->default('status_change');
            $table->text('note')->nullable();
            $table->boolean('automated')->default(false);
            $table->timestamp('occurred_at');
            $table->timestamps();
        });

        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prior_auth_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('label');
            $table->string('file_name');
            $table->string('mime_type')->default('application/pdf');
            $table->unsignedInteger('file_size_kb')->default(0);
            $table->string('category')->default('clinical');
            $table->string('storage_path')->nullable();
            $table->boolean('included_in_packet')->default(true);
            $table->timestamps();
        });

        Schema::create('agent_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prior_auth_request_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('triggered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('run_type')->default('evidence_gather');
            $table->string('status')->default('completed');
            $table->unsignedInteger('steps')->default(0);
            $table->json('actions')->nullable();
            $table->text('recommendation')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_runs');
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('status_events');
        Schema::dropIfExists('denials');
        Schema::dropIfExists('payer_submissions');
        Schema::dropIfExists('clinical_evidences');
        Schema::dropIfExists('prior_auth_requests');
        Schema::dropIfExists('payers');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('module_settings');
    }
};
