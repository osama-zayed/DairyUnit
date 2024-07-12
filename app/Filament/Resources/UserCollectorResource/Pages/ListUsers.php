<?php

namespace App\Filament\Resources\UserCollectorResource\Pages;

use App\Filament\Resources\UserCollectorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserCollectorResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
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
        $query->where('user_type','collector');
    
        return $query;
    }
}