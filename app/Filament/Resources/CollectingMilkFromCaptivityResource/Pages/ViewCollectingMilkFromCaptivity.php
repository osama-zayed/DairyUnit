<?php

namespace App\Filament\Resources\CollectingMilkFromCaptivityResource\Pages;

use App\Filament\Resources\CollectingMilkFromCaptivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCollectingMilkFromCaptivity extends ViewRecord
{
    protected static string $resource = CollectingMilkFromCaptivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
