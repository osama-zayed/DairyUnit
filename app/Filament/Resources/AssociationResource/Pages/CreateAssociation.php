<?php

namespace App\Filament\Resources\AssociationResource\Pages;

use App\Filament\Resources\AssociationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Spatie\Permission\Models\Role;

class CreateAssociation extends CreateRecord
{
    protected static string $resource = AssociationResource::class;
    protected function afterCreate(): void
    {
        $user = $this->record;
        $role = Role::where('name', 'association')->first();
        if ($role) {
            $user->syncRoles([$role->id]);
        }
    }
}
