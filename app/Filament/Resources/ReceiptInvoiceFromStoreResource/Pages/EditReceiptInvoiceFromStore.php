<?php

namespace App\Filament\Resources\ReceiptInvoiceFromStoreResource\Pages;

use App\Filament\Resources\ReceiptInvoiceFromStoreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReceiptInvoiceFromStore extends EditRecord
{
    protected static string $resource = ReceiptInvoiceFromStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
}
