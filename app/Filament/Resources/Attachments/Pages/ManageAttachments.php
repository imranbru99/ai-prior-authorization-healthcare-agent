<?php

namespace App\Filament\Resources\Attachments\Pages;

use App\Filament\Resources\Attachments\AttachmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAttachments extends ManageRecords
{
    protected static string $resource = AttachmentResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
