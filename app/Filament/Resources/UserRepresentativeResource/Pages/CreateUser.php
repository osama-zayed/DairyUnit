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

    protected function handleRecordCreation(array $data): Model
    {
        $user = User::create($data);

        // Attach the 'representative' role to the user
        $representative = Role::where('name', 'representative')->firstOrFail();
        $user->roles()->attach($representative);

        return $user;
    }
}