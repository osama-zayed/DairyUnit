<?php

namespace App\Filament\Resources\UserCollectorResource\Pages;

use App\Filament\Resources\UserCollectorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserCollectorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }
    protected function authorizeAccess(): void
    {
        if ($this->getRecord()->user_type !=  "collector") {
            abort(404);
        }
    }
}
