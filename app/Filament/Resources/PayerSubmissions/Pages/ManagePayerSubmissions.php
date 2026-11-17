<?php

namespace App\Filament\Resources\PayerSubmissions\Pages;

use App\Filament\Resources\PayerSubmissions\PayerSubmissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePayerSubmissions extends ManageRecords
{
    protected static string $resource = PayerSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
