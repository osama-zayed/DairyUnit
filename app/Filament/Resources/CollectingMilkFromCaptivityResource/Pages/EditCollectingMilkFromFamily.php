<?php

namespace App\Filament\Resources\CollectingMilkFromFamilyResource\Pages;

use App\Filament\Resources\CollectingMilkFromFamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCollectingMilkFromFamily extends EditRecord
{
    protected static string $resource = CollectingMilkFromFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
