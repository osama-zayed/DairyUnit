<?php

namespace App\Filament\Resources\ReceiptFromAssociationResource\Pages;

use App\Filament\Resources\ReceiptFromAssociationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReceiptFromAssociations extends ListRecords
{
    protected static string $resource = ReceiptFromAssociationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
