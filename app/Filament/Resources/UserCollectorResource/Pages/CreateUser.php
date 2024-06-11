<?php

namespace App\Filament\Resources\UserCollectorResource\Pages;

use App\Filament\Resources\UserCollectorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserCollectorResource::class;
}
