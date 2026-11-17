<?php

namespace App\Filament\Resources\Denials\Pages;

use App\Filament\Resources\Denials\DenialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDenials extends ManageRecords
{
    protected static string $resource = DenialResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
