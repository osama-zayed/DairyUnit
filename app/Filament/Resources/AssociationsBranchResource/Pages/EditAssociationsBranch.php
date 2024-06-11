<?php

namespace App\Filament\Resources\AssociationsBranchResource\Pages;

use App\Filament\Resources\AssociationsBranchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssociationsBranch extends EditRecord
{
    protected static string $resource = AssociationsBranchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
