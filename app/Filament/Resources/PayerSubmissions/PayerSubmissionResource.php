<?php

namespace App\Filament\Resources\PayerSubmissions;

use App\Filament\Resources\PayerSubmissions\Pages\ManagePayerSubmissions;
use App\Models\PayerSubmission;
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

class PayerSubmissionResource extends Resource
{
    protected static ?string $model = PayerSubmission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperAirplane;

    protected static string|UnitEnum|null $navigationGroup = 'Authorization';

    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('payer_submissions');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('prior_auth_request_id')->relationship('priorAuthRequest', 'case_number')->searchable()->required(),
            Select::make('submitted_by')->relationship('submitter', 'name')->searchable(),
            Select::make('channel')->options([
                'portal' => 'Portal',
                'fax' => 'Fax',
                'edi' => 'EDI',
                'api' => 'API',
                'phone' => 'Phone',
            ])->required()->default('portal'),
            TextInput::make('external_reference')->maxLength(80),
            Select::make('status')->options([
                'queued' => 'Queued',
                'submitted' => 'Submitted',
                'acknowledged' => 'Acknowledged',
                'failed' => 'Failed',
            ])->required()->default('queued'),
            DateTimePicker::make('submitted_at'),
            DateTimePicker::make('acknowledged_at'),
            Textarea::make('response_notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('priorAuthRequest.case_number')->label('Case'),
                TextColumn::make('channel')->badge(),
                TextColumn::make('external_reference')->label('Reference'),
                TextColumn::make('status')->badge(),
                TextColumn::make('submitted_at')->dateTime(),
                TextColumn::make('submitter.name')->label('By')->toggleable(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManagePayerSubmissions::route('/')];
    }
}
