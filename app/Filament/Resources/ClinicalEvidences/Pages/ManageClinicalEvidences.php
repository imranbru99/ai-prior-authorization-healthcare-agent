<?php

namespace App\Filament\Resources\ClinicalEvidences\Pages;

use App\Filament\Resources\ClinicalEvidences\ClinicalEvidenceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageClinicalEvidences extends ManageRecords
{
    protected static string $resource = ClinicalEvidenceResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
