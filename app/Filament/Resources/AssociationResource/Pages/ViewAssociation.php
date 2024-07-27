<?php

namespace App\Filament\Resources\AssociationResource\Pages;

use App\Filament\Resources\AssociationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAssociation extends ViewRecord
{
    protected static string $resource = AssociationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    protected function authorizeAccess(): void
    {
        if ($this->getRecord()->user_type !=  "association") {
            abort(404);
        }
    }
}
