<?php

namespace App\Filament\Resources\PriorAuthRequests;

use App\Filament\Resources\PriorAuthRequests\Pages\ManagePriorAuthRequests;
use App\Models\PriorAuthRequest;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class PriorAuthRequestResource extends Resource
{
    protected static ?string $model = PriorAuthRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Authorization';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Prior Auth Cases';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('prior_auth');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('case_number')->required()->unique(ignoreRecord: true)->maxLength(40),
            Select::make('patient_id')->relationship('patient', 'mrn')->searchable()->required(),
            Select::make('payer_id')->relationship('payer', 'name')->searchable()->required(),
            Select::make('assigned_to')->relationship('assignee', 'name')->searchable(),
            TextInput::make('service_code')->required()->maxLength(40),
            TextInput::make('diagnosis_codes')->maxLength(120),
            TextInput::make('service_description')->required()->maxLength(255),
            Select::make('urgency')->options([
                'routine' => 'Routine',
                'urgent' => 'Urgent',
                'emergent' => 'Emergent',
            ])->required()->default('routine'),
            Select::make('status')->options([
                'draft' => 'Draft',
                'evidence_gathering' => 'Evidence gathering',
                'ready' => 'Ready',
                'submitted' => 'Submitted',
                'pending' => 'Pending payer',
                'approved' => 'Approved',
                'denied' => 'Denied',
                'appealed' => 'Appealed',
                'closed' => 'Closed',
            ])->required()->default('draft'),
            DatePicker::make('requested_service_date'),
            DatePicker::make('due_date'),
            TextInput::make('evidence_score')->numeric()->minValue(0)->maxValue(100)->default(0),
            Toggle::make('ai_assisted')->default(false),
            Textarea::make('clinical_summary')->columnSpanFull(),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('case_number')->searchable()->sortable(),
                TextColumn::make('patient.full_name')->label('Patient'),
                TextColumn::make('payer.name')->label('Payer')->toggleable(),
                TextColumn::make('service_code')->label('CPT/HCPCS'),
                TextColumn::make('urgency')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('evidence_score')->label('Evidence'),
                IconColumn::make('ai_assisted')->boolean(),
                TextColumn::make('due_date')->date()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'draft' => 'Draft',
                    'submitted' => 'Submitted',
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'denied' => 'Denied',
                ]),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManagePriorAuthRequests::route('/')];
    }
}
