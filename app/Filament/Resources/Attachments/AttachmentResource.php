<?php

namespace App\Filament\Resources\Attachments;

use App\Filament\Resources\Attachments\Pages\ManageAttachments;
use App\Models\Attachment;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class AttachmentResource extends Resource
{
    protected static ?string $model = Attachment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperClip;

    protected static string|UnitEnum|null $navigationGroup = 'Authorization';

    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('attachments');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('prior_auth_request_id')->relationship('priorAuthRequest', 'case_number')->searchable()->required(),
            Select::make('uploaded_by')->relationship('uploader', 'name')->searchable(),
            TextInput::make('label')->required()->maxLength(150),
            TextInput::make('file_name')->required()->maxLength(200),
            TextInput::make('mime_type')->default('application/pdf')->maxLength(80),
            TextInput::make('file_size_kb')->numeric()->default(0),
            Select::make('category')->options([
                'clinical' => 'Clinical',
                'imaging' => 'Imaging',
                'lmn' => 'LMN',
                'admin' => 'Admin',
                'appeal' => 'Appeal',
            ])->default('clinical'),
            TextInput::make('storage_path')->maxLength(255),
            Toggle::make('included_in_packet')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('priorAuthRequest.case_number')->label('Case'),
                TextColumn::make('label')->searchable(),
                TextColumn::make('category')->badge(),
                TextColumn::make('file_name')->toggleable(),
                TextColumn::make('file_size_kb')->label('KB'),
                IconColumn::make('included_in_packet')->boolean()->label('In packet'),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageAttachments::route('/')];
    }
}
