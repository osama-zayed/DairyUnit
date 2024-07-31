<?php

namespace App\Filament\Resources\ReturnTheQuantityResource\Pages;

use App\Filament\Resources\ReturnTheQuantityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReturnTheQuantities extends ListRecords
{
    protected static string $resource = ReturnTheQuantityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
