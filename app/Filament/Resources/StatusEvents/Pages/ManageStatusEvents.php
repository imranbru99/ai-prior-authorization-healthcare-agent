<?php

namespace App\Filament\Resources\StatusEvents\Pages;

use App\Filament\Resources\StatusEvents\StatusEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageStatusEvents extends ManageRecords
{
    protected static string $resource = StatusEventResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
