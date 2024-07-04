<?php

namespace App\Filament\Resources\AssociationResource\Pages;

use App\Filament\Resources\AssociationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class CreateAssociation extends CreateRecord
{
    protected static string $resource = AssociationResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        $data['user_type'] = 'association';
        $user = User::create($data);

        return $user;
    }
}
