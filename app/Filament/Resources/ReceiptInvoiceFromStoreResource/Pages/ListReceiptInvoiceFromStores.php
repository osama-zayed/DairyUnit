<?php

namespace App\Filament\Resources\ReceiptInvoiceFromStoreResource\Pages;

use App\Filament\Resources\ReceiptInvoiceFromStoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReceiptInvoiceFromStores extends ListRecords
{
    protected static string $resource = ReceiptInvoiceFromStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
