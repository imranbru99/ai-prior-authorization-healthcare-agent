@extends('layouts.marketing')

@section('title', 'Demo Access')
@section('meta_description', 'Demo users for AI Prior Authorization Agent — login to Filament admin with seeded healthcare RCM data.')

@section('content')
<section class="section">
    <span class="pill">Try it now</span>
    <h2>Demo credentials</h2>
    <p class="muted">Password for every seeded user: <strong>password</strong>. Open <a href="/admin">/admin</a> after migrate --seed.</p>
    <div class="panel" style="margin-top:1.5rem; overflow-x:auto;">
        <table class="pricing">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>Demo</td><td><code>demo@priorauth-ai.test</code></td></tr>
                <tr><td>Super Admin</td><td><code>superadmin@priorauth-ai.test</code></td></tr>
                <tr><td>Admin</td><td><code>admin@priorauth-ai.test</code></td></tr>
                <tr><td>Manager</td><td><code>manager@priorauth-ai.test</code></td></tr>
                <tr><td>Staff</td><td><code>staff@priorauth-ai.test</code></td></tr>
                <tr><td>Client</td><td><code>client@priorauth-ai.test</code></td></tr>
            </tbody>
        </table>
    </div>
    <div class="cta-row" style="margin-top:1.25rem;">
        <a class="btn" href="/admin">Open Filament admin</a>
        <a class="btn btn-outline" href="{{ route('modules') }}">View modules</a>
    </div>
</section>
@endsection
