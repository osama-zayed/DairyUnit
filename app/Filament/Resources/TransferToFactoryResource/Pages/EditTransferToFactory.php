<?php

namespace App\Filament\Resources\TransferToFactoryResource\Pages;

use App\Filament\Resources\TransferToFactoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransferToFactory extends EditRecord
{
    protected static string $resource = TransferToFactoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}
