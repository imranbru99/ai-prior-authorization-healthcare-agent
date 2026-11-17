@extends('layouts.marketing')

@section('title', 'Features')
@section('meta_description', 'Prior authorization features: clinical evidence, payer submissions, denial tracking, status workflows, attachments, and AI agent runs.')

@section('content')
<section class="section">
    <span class="pill">Product capabilities</span>
    <h2>Everything a modern PA desk needs</h2>
    <p class="muted">From first clinical signal to appeal overturn — one Filament workspace for specialists, managers, and AI copilots.</p>
    <div class="grid" style="margin-top:1.5rem;">
        <article class="panel">
            <h3>Clinical evidence engine</h3>
            <p class="muted">AI extracts progress notes, imaging, labs, and guideline references, then scores packet completeness.</p>
        </article>
        <article class="panel">
            <h3>Payer submissions</h3>
            <p class="muted">Portal, fax, EDI, and API channels with confirmation IDs and acknowledgment timestamps.</p>
        </article>
        <article class="panel">
            <h3>Denial recovery</h3>
            <p class="muted">Denial codes, appeal deadlines, turnaround hours, and overturn outcomes in one queue.</p>
        </article>
        <article class="panel">
            <h3>Status workflows</h3>
            <p class="muted">Immutable timeline from draft → evidence → submitted → pending → approved/denied/appealed.</p>
        </article>
        <article class="panel">
            <h3>Attachment packets</h3>
            <p class="muted">LMNs, imaging PDFs, and clinical docs toggled into the submission packet.</p>
        </article>
        <article class="panel">
            <h3>AI agent runs</h3>
            <p class="muted">Evidence gather, packet draft, appeal assist, and status check recommendations.</p>
        </article>
    </div>
</section>
@endsection
