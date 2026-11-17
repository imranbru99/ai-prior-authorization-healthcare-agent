<?php

namespace App\Filament\Widgets;

use App\Models\Denial;
use App\Services\ModuleManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class DenialQueueWidget extends TableWidget
{
    protected static ?string $heading = 'Denial recovery queue';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return app(ModuleManager::class)->enabled('denial_tracking');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Denial::query()->with('priorAuthRequest')->latest()->limit(8))
            ->columns([
                TextColumn::make('priorAuthRequest.case_number')->label('Case'),
                TextColumn::make('denial_code')->label('Code')->badge(),
                TextColumn::make('reason')->limit(40),
                TextColumn::make('status')->badge(),
                TextColumn::make('appeal_deadline')->date(),
                TextColumn::make('turnaround_hours')->label('Hours'),
            ])
            ->paginated(false);
    }
}
