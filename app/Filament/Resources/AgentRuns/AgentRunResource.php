<?php

namespace App\Filament\Resources\AgentRuns;

use App\Filament\Resources\AgentRuns\Pages\ManageAgentRuns;
use App\Models\AgentRun;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class AgentRunResource extends Resource
{
    protected static ?string $model = AgentRun::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static string|UnitEnum|null $navigationGroup = 'AI Agent';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'AI Agent Runs';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('ai_agent');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('prior_auth_request_id')->relationship('priorAuthRequest', 'case_number')->searchable(),
            Select::make('triggered_by')->relationship('triggerer', 'name')->searchable(),
            Select::make('run_type')->options([
                'evidence_gather' => 'Evidence gather',
                'packet_draft' => 'Packet draft',
                'appeal_assist' => 'Appeal assist',
                'status_check' => 'Status check',
            ])->required()->default('evidence_gather'),
            Select::make('status')->options([
                'queued' => 'Queued',
                'running' => 'Running',
                'completed' => 'Completed',
                'failed' => 'Failed',
            ])->required()->default('completed'),
            TextInput::make('steps')->numeric()->default(0),
            Textarea::make('recommendation')->columnSpanFull(),
            DateTimePicker::make('started_at'),
            DateTimePicker::make('finished_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('priorAuthRequest.case_number')->label('Case'),
                TextColumn::make('run_type')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('steps'),
                TextColumn::make('recommendation')->limit(50),
                TextColumn::make('finished_at')->dateTime(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageAgentRuns::route('/')];
    }
}
