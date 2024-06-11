<?php

namespace App\Filament\Resources\CollectingMilkFromCaptivityResource\Pages;

use App\Filament\Resources\CollectingMilkFromCaptivityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCollectingMilkFromCaptivity extends EditRecord
{
    protected static string $resource = CollectingMilkFromCaptivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
