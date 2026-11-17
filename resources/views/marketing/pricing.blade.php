@extends('layouts.marketing')

@section('title', 'Pricing')
@section('meta_description', 'Pricing for AI Prior Authorization Agent — starter, growth, and enterprise RCM plans.')

@section('content')
<section class="section">
    <span class="pill">Transparent packaging</span>
    <h2>Plans sized for RCM teams</h2>
    <p class="muted">Demo credentials unlock the full stack locally. Production packaging is typically seat + case volume based.</p>
    <div class="panel" style="margin-top:1.5rem; overflow-x:auto;">
        <table class="pricing">
            <thead>
                <tr>
                    <th>Plan</th>
                    <th>Best for</th>
                    <th>Includes</th>
                    <th>Indicative</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Starter</strong></td>
                    <td>Single specialty clinic</td>
                    <td>Prior auth + evidence + attachments</td>
                    <td>From $799/mo</td>
                </tr>
                <tr>
                    <td><strong>Growth</strong></td>
                    <td>Multi-site RCM desks</td>
                    <td>All modules except attendance</td>
                    <td>From $2,400/mo</td>
                </tr>
                <tr>
                    <td><strong>Enterprise</strong></td>
                    <td>Health systems / BPOs</td>
                    <td>All modules + SSO + dedicated AI hub</td>
                    <td>Custom</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
@endsection
