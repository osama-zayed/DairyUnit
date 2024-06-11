<?php

namespace App\Filament\Resources\CollectingMilkFromCaptivityResource\Pages;

use App\Filament\Resources\CollectingMilkFromCaptivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCollectingMilkFromCaptivities extends ListRecords
{
    protected static string $resource = CollectingMilkFromCaptivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
