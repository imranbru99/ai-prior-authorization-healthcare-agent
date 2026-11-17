<?php

namespace App\Filament\Resources\Denials;

use App\Filament\Resources\Denials\Pages\ManageDenials;
use App\Models\Denial;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class DenialResource extends Resource
{
    protected static ?string $model = Denial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static string|UnitEnum|null $navigationGroup = 'Recovery';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('denial_tracking');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('prior_auth_request_id')->relationship('priorAuthRequest', 'case_number')->searchable()->required(),
            Select::make('payer_submission_id')->relationship('payerSubmission', 'external_reference')->searchable(),
            TextInput::make('denial_code')->maxLength(40),
            TextInput::make('reason')->required()->maxLength(255),
            Select::make('status')->options([
                'open' => 'Open',
                'appealed' => 'Appealed',
                'overturned' => 'Overturned',
                'upheld' => 'Upheld',
                'closed' => 'Closed',
            ])->required()->default('open'),
            DatePicker::make('appeal_deadline'),
            DateTimePicker::make('appealed_at'),
            DateTimePicker::make('resolved_at'),
            Select::make('outcome')->options([
                'overturned' => 'Overturned',
                'upheld' => 'Upheld',
                'partial' => 'Partial',
                'withdrawn' => 'Withdrawn',
            ]),
            TextInput::make('turnaround_hours')->numeric(),
            Textarea::make('appeal_notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('priorAuthRequest.case_number')->label('Case'),
                TextColumn::make('denial_code')->badge(),
                TextColumn::make('reason')->limit(40),
                TextColumn::make('status')->badge(),
                TextColumn::make('appeal_deadline')->date(),
                TextColumn::make('turnaround_hours')->label('Hours'),
                TextColumn::make('outcome')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'open' => 'Open',
                    'appealed' => 'Appealed',
                    'overturned' => 'Overturned',
                    'upheld' => 'Upheld',
                ]),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageDenials::route('/')];
    }
}
