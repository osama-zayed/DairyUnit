<?php

namespace App\Filament\Resources\ReturnTheQuantityResource\Pages;

use App\Filament\Resources\ReturnTheQuantityResource;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;

class ListReturnTheQuantities extends ListRecords
{
    protected static string $resource = ReturnTheQuantityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();
        $query->where('association_id', '!=', null);
        return $query;
    }
}
