<?php

namespace App\Filament\Resources\StatusEvents;

use App\Filament\Resources\StatusEvents\Pages\ManageStatusEvents;
use App\Models\StatusEvent;
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
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class StatusEventResource extends Resource
{
    protected static ?string $model = StatusEvent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static string|UnitEnum|null $navigationGroup = 'Authorization';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Status Workflows';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('status_workflows');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('prior_auth_request_id')->relationship('priorAuthRequest', 'case_number')->searchable()->required(),
            Select::make('actor_id')->relationship('actor', 'name')->searchable(),
            TextInput::make('from_status')->maxLength(40),
            TextInput::make('to_status')->required()->maxLength(40),
            TextInput::make('event_type')->default('status_change')->maxLength(60),
            Textarea::make('note')->columnSpanFull(),
            Toggle::make('automated')->default(false),
            DateTimePicker::make('occurred_at')->required()->default(now()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('priorAuthRequest.case_number')->label('Case'),
                TextColumn::make('from_status')->label('From'),
                TextColumn::make('to_status')->label('To')->badge(),
                TextColumn::make('event_type'),
                IconColumn::make('automated')->boolean(),
                TextColumn::make('occurred_at')->dateTime()->sortable(),
                TextColumn::make('actor.name')->toggleable(),
            ])
            ->defaultSort('occurred_at', 'desc')
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageStatusEvents::route('/')];
    }
}
