<?php

namespace App\Filament\Resources\ReceiptFromAssociationResource\Pages;

use App\Filament\Resources\ReceiptFromAssociationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReceiptFromAssociation extends EditRecord
{
    protected static string $resource = ReceiptFromAssociationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
