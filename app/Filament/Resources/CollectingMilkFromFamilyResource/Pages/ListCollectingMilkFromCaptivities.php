<?php

namespace App\Filament\Resources\CollectingMilkFromFamilyResource\Pages;

use App\Filament\Resources\CollectingMilkFromFamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCollectingMilkFromCaptivities extends ListRecords
{
    protected static string $resource = CollectingMilkFromFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
