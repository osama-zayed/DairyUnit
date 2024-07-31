<?php

namespace App\Filament\Resources\ReturnTheQuantityToAssociationResource\Pages;

use App\Filament\Resources\ReturnTheQuantityToAssociationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReturnTheQuantity extends EditRecord
{
    protected static string $resource = ReturnTheQuantityToAssociationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}
