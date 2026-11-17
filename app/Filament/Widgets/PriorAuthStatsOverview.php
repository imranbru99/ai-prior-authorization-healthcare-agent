<?php

namespace App\Filament\Widgets;

use App\Models\Denial;
use App\Models\PriorAuthRequest;
use App\Models\PayerSubmission;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PriorAuthStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Open PA cases', PriorAuthRequest::query()->whereNotIn('status', ['approved', 'closed'])->count())
                ->description('Active authorization workqueue')
                ->color('primary'),
            Stat::make('Submitted to payers', PayerSubmission::query()->where('status', 'submitted')->count())
                ->description('Awaiting payer acknowledgment')
                ->color('info'),
            Stat::make('Open denials', Denial::query()->whereIn('status', ['open', 'appealed'])->count())
                ->description('Appeal / recovery pipeline')
                ->color('danger'),
            Stat::make('Avg evidence score', (string) (int) round(PriorAuthRequest::query()->avg('evidence_score') ?? 0))
                ->description('Packet completeness (0–100)')
                ->color('success'),
        ];
    }
}
