<?php

namespace App\Filament\Resources\UserRepresentativeResource\Pages;

use App\Filament\Resources\UserRepresentativeResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserRepresentativeResource::class;

    protected function afterCreate(): void
    {
        $user = $this->record;
        $role = Role::where('name', 'representative')->first();
        if ($role) {
            $user->syncRoles([$role->id]);
        }
    }
}