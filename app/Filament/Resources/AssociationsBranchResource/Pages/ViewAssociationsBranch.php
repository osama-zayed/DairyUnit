<?php

namespace App\Filament\Resources\AssociationsBranchResource\Pages;

use App\Filament\Resources\AssociationsBranchResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAssociationsBranch extends ViewRecord
{
    protected static string $resource = AssociationsBranchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
