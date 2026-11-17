<?php

namespace App\Filament\Resources\PriorAuthRequests\Pages;

use App\Filament\Resources\PriorAuthRequests\PriorAuthRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePriorAuthRequests extends ManageRecords
{
    protected static string $resource = PriorAuthRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
