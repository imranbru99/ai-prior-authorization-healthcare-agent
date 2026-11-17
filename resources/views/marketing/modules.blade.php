@extends('layouts.marketing')

@section('title', 'Modules')
@section('meta_description', 'Toggleable prior authorization modules for patients, payers, clinical evidence, submissions, denials, workflows, attachments, and AI.')

@section('content')
<section class="section">
    <span class="pill">Modular architecture</span>
    <h2>Enable only the domains you run</h2>
    <p class="muted">Config + Filament Modules resource control navigation and service gates. Disabled modules hide from staff and refuse work.</p>
    <div class="grid" style="margin-top:1.5rem;">
        @foreach($modules as $module)
            <article class="panel">
                <span class="pill">{{ $module['key'] }} · {{ $module['enabled'] ? 'ON' : 'OFF' }}</span>
                <h3 style="margin:0.6rem 0 0.35rem;font-family:'Source Serif 4',Georgia,serif;">{{ $module['name'] }}</h3>
                <p class="muted" style="margin:0;">{{ $module['description'] }}</p>
            </article>
        @endforeach
    </div>
</section>
@endsection
