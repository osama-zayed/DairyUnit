<?php

namespace App\Filament\Resources\ReturnTheQuantityToAssociationResource\Pages;

use App\Filament\Resources\ReturnTheQuantityToAssociationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewReturnTheQuantity extends ViewRecord
{
    protected static string $resource = ReturnTheQuantityToAssociationResource::class;

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
