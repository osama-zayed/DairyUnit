<?php

namespace App\Filament\Widgets;

use App\Models\Driver;
use App\Models\Factory;
use App\Models\Family;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $driverCount = Driver::count();
        $factoryCount = Factory::count();
        $familyCount = Family::count();
        $associationCount = User::where("user_type", 'association')->count();
        $representativeCount = User::where("user_type", 'representative')->count();
        $collectorCount = User::where("user_type", 'collector')->count();
        return [
            Stat::make('عدد الجمعيات', $associationCount)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([15, 2, 5, 3, 15, 4, 5])
                ->color('info'),
            Stat::make('عدد المناديب', $representativeCount)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([15, 5, 10, 3, 5, 5, 15])
                ->color('info'),
            Stat::make('عدد المجمعين', $collectorCount)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info'),
            Stat::make('عدد المصانع', $factoryCount)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([170, 160, 140, 100, 140, 130, 120])
                ->color('info'),
            Stat::make('عدد السائقين', $driverCount)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([1, 5, 10, 3, 15, 4, 17])
                ->color('info'),
            Stat::make('عدد الاسر المنتجه', $familyCount)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([2, 8, 15, 3, 15, 0, 7])
                ->color('info'),
        ];
    }
}