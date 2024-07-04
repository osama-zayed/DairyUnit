<?php

namespace App\Filament\Resources\UserCollectorResource\Pages;

use App\Filament\Resources\UserCollectorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserCollectorResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        $data['user_type'] = 'collector';
        $user = User::create($data);

        return $user;
    }
}
