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
            Stat::make('عدد الجمعيات', $associationCount),
            Stat::make('عدد المناديب', $representativeCount),
            Stat::make('عدد المجمعين', $collectorCount),
            Stat::make('عدد المصانع', $factoryCount),
            Stat::make('عدد السائقين', $driverCount),
            Stat::make('عدد الاسر المنتجه', $familyCount),
        ];
    }
}
