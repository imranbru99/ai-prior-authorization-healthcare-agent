<?php

namespace App\Filament\Resources\ClinicalEvidences;

use App\Filament\Resources\ClinicalEvidences\Pages\ManageClinicalEvidences;
use App\Models\ClinicalEvidence;
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

class ClinicalEvidenceResource extends Resource
{
    protected static ?string $model = ClinicalEvidence::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static string|UnitEnum|null $navigationGroup = 'Authorization';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Clinical Evidence';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('clinical_evidence');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('prior_auth_request_id')->relationship('priorAuthRequest', 'case_number')->searchable()->required(),
            Select::make('evidence_type')->options([
                'progress_note' => 'Progress note',
                'imaging' => 'Imaging',
                'lab' => 'Lab',
                'guideline' => 'Guideline',
                'lmn' => 'Letter of medical necessity',
            ])->required(),
            TextInput::make('source')->default('ehr')->maxLength(80),
            TextInput::make('title')->required()->maxLength(200),
            Textarea::make('summary')->columnSpanFull(),
            TextInput::make('guideline_reference')->maxLength(200),
            TextInput::make('relevance_score')->numeric()->minValue(0)->maxValue(100)->default(50),
            Toggle::make('ai_extracted')->default(false),
            Select::make('status')->options([
                'collected' => 'Collected',
                'reviewed' => 'Reviewed',
                'insufficient' => 'Insufficient',
            ])->default('collected'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('priorAuthRequest.case_number')->label('Case'),
                TextColumn::make('evidence_type')->badge(),
                TextColumn::make('title')->limit(40)->searchable(),
                TextColumn::make('relevance_score')->label('Score'),
                IconColumn::make('ai_extracted')->boolean(),
                TextColumn::make('status')->badge(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageClinicalEvidences::route('/')];
    }
}
