<?php

namespace App\Filament\Resources\UserCollectorResource\Pages;

use App\Filament\Resources\UserCollectorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Filament\Resources\CollectingMilkFromCaptivityResource;
use Spatie\Permission\Models\Role;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserCollectorResource::class;
    protected function afterCreate(): void
    {
        $user = $this->record;
        $role = Role::where('name', 'collector')->first();
        if ($role) {
            $user->syncRoles([$role->id]);
        }
    }
}
