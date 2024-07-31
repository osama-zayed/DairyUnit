<?php

namespace App\Filament\Resources\ReturnTheQuantityResource\Pages;

use App\Filament\Resources\ReturnTheQuantityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReturnTheQuantity extends EditRecord
{
    protected static string $resource = ReturnTheQuantityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}
