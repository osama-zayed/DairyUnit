<?php

namespace App\Filament\Resources\TransferToFactoryResource\Pages;

use App\Filament\Resources\TransferToFactoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTransferToFactory extends ViewRecord
{
    protected static string $resource = TransferToFactoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }
}
