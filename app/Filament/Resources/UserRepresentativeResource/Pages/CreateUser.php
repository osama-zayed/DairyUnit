<?php

namespace App\Filament\Resources\UserRepresentativeResource\Pages;

use App\Filament\Resources\UserRepresentativeResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserRepresentativeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $data['user_type'] = 'representative';
        $user = User::create($data);

        return $user;
    }
}