@extends('layouts.marketing')

@section('title', 'AI Prior Authorization Healthcare Agent')
@section('meta_description', 'AI prior authorization healthcare agent that gathers clinical evidence, submits payer requests, tracks status, and reduces denial turnaround time.')

@section('content')
<section class="hero">
    <span class="pill">Healthcare RCM · Modular Laravel + Filament</span>
    <h1>AI Prior Authorization Agent</h1>
    <p>
        Cut days off authorization and denial cycles. Gather clinical evidence, assemble payer packets,
        submit requests, track status workflows, and recover denials — with every module toggleable for your health system.
    </p>
    <div class="cta-row">
        <a class="btn" href="{{ route('demo') }}">Try demo credentials</a>
        <a class="btn btn-outline" href="{{ route('features') }}">Explore features</a>
    </div>
</section>

<section class="section">
    <img class="dash-shot" src="{{ asset('docs/screenshots/dashboard.png') }}" alt="PriorAuth AI command center dashboard with teal accent">
    <p class="muted" style="margin-top:0.75rem;">Authorization Command Center — open cases, evidence scores, payer submissions, and denial recovery.</p>
</section>

<section class="section">
    <h2>Toggle only what your RCM team needs</h2>
    <p class="muted">Run lean utilization management or full denial recovery. Modules turn on and off without rewriting the platform.</p>
    <div class="grid" style="margin-top:1.25rem;">
        @foreach($modules as $module)
            <article class="panel">
                <span class="pill">{{ $module['enabled'] ? 'Enabled' : 'Optional' }}</span>
                <h3 style="margin:0.6rem 0 0.35rem;font-family:'Source Serif 4',Georgia,serif;">{{ $module['name'] }}</h3>
                <p class="muted" style="margin:0;">{{ $module['description'] }}</p>
            </article>
        @endforeach
    </div>
</section>
@endsection
