<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Toggleable prior-authorization modules
    |--------------------------------------------------------------------------
    | Health systems enable only the revenue-cycle domains they need.
    */
    'patients' => [
        'enabled' => (bool) env('MODULE_PATIENTS', true),
        'name' => 'Patients',
        'description' => 'Member demographics, MRNs, and care-team assignment for PA workqueues.',
    ],
    'payers' => [
        'enabled' => (bool) env('MODULE_PAYERS', true),
        'name' => 'Payer Directory',
        'description' => 'Commercial, Medicare Advantage, and Medicaid payer profiles with portal hints.',
    ],
    'prior_auth' => [
        'enabled' => (bool) env('MODULE_PRIOR_AUTH', true),
        'name' => 'Prior Auth Requests',
        'description' => 'CPT/HCPCS/ICD-driven authorization cases with urgency and due dates.',
    ],
    'clinical_evidence' => [
        'enabled' => (bool) env('MODULE_CLINICAL_EVIDENCE', true),
        'name' => 'Clinical Evidence',
        'description' => 'AI-assisted chart mining, guideline mapping, and evidence completeness scores.',
    ],
    'payer_submissions' => [
        'enabled' => (bool) env('MODULE_PAYER_SUBMISSIONS', true),
        'name' => 'Payer Submissions',
        'description' => 'Portal, fax, EDI, and API submissions with confirmation tracking.',
    ],
    'denial_tracking' => [
        'enabled' => (bool) env('MODULE_DENIAL_TRACKING', true),
        'name' => 'Denial Tracking',
        'description' => 'Denial codes, appeal deadlines, overturn outcomes, and turnaround KPIs.',
    ],
    'status_workflows' => [
        'enabled' => (bool) env('MODULE_STATUS_WORKFLOWS', true),
        'name' => 'Status Workflows',
        'description' => 'Status timeline events from draft → submitted → pending → approved/denied.',
    ],
    'attachments' => [
        'enabled' => (bool) env('MODULE_ATTACHMENTS', true),
        'name' => 'Attachments',
        'description' => 'Clinical notes, imaging reports, letters of medical necessity, and PDFs.',
    ],
    'ai_agent' => [
        'enabled' => (bool) env('MODULE_AI_AGENT', true),
        'name' => 'AI Prior Auth Agent',
        'description' => 'Agent runs that gather evidence, draft packets, and recommend next actions.',
    ],
    'attendance' => [
        'enabled' => (bool) env('MODULE_ATTENDANCE', false),
        'name' => 'Staff Attendance',
        'description' => 'Optional biometric/time-clock hub for RCM and utilization-management staff.',
    ],
];
