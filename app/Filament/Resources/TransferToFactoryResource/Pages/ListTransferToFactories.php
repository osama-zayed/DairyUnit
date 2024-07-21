<?php

namespace App\Filament\Resources\TransferToFactoryResource\Pages;

use App\Filament\Resources\TransferToFactoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransferToFactories extends ListRecords
{
    protected static string $resource = TransferToFactoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
