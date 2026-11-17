<?php

namespace App\Filament\Resources\Payers;

use App\Filament\Resources\Payers\Pages\ManagePayers;
use App\Models\Payer;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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

class PayerResource extends Resource
{
    protected static ?string $model = Payer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|UnitEnum|null $navigationGroup = 'Clinical Ops';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('payers');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(150),
            TextInput::make('payer_id')->required()->unique(ignoreRecord: true)->maxLength(50),
            Select::make('plan_type')->options([
                'commercial' => 'Commercial',
                'medicare_advantage' => 'Medicare Advantage',
                'medicaid' => 'Medicaid',
                'workers_comp' => 'Workers Comp',
            ])->required(),
            TextInput::make('portal_url')->url()->maxLength(255),
            TextInput::make('avg_turnaround_days')->numeric()->default(7),
            Toggle::make('supports_api')->default(false),
            Toggle::make('is_active')->default(true),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('payer_id')->label('Payer ID'),
                TextColumn::make('plan_type')->badge(),
                TextColumn::make('avg_turnaround_days')->label('Avg days'),
                IconColumn::make('supports_api')->boolean(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManagePayers::route('/')];
    }
}
