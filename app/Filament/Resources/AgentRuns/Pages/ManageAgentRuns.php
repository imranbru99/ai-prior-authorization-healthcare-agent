<?php

namespace App\Filament\Resources\AgentRuns\Pages;

use App\Filament\Resources\AgentRuns\AgentRunResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAgentRuns extends ManageRecords
{
    protected static string $resource = AgentRunResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
