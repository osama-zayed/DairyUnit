<?php

namespace App\Filament\Resources\CollectingMilkFromFamilyResource\Pages;

use App\Filament\Resources\CollectingMilkFromFamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCollectingMilkFromFamily extends ViewRecord
{
    protected static string $resource = CollectingMilkFromFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }
}
