<?php

namespace App\Filament\Resources\UserRepresentativeResource\Pages;

use App\Filament\Resources\UserRepresentativeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserRepresentativeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    protected function authorizeAccess(): void
    {
        if ($this->getRecord()->user_type !=  "representative") {
            abort(404);
        }
    }
}
