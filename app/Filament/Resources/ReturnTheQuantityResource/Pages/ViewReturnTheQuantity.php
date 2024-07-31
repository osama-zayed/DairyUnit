<?php

namespace App\Filament\Resources\ReturnTheQuantityResource\Pages;

use App\Filament\Resources\ReturnTheQuantityResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewReturnTheQuantity extends ViewRecord
{
    protected static string $resource = ReturnTheQuantityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }
    protected function authorizeAccess(): void
    {
        if (is_null($this->getRecord()->association_id)) {
            abort(404);
        }
    }
}
