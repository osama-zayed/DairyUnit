<?php

namespace App\Filament\Resources\UserRepresentativeResource\Pages;

use App\Filament\Resources\UserRepresentativeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserRepresentativeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();
    
        // Exclude the currently logged-in user from the table
        if ($user = auth()->user()) {
            $query->where('id', '!=', $user->id);
        }
    
        // Filter the table to only show users with the 'collector' role
        $query->whereHas('roles', function ($roleQuery) {
            $roleQuery->where('name', 'representative');
        });
    
        return $query;
    }
}