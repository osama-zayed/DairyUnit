<?php

namespace App\Filament\Resources\CollectingMilkFromFamilyResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\CollectingMilkFromFamily;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
class CollectingMilkFromFamilyChart extends ChartWidget
{
    protected static ?string $heading = 'عمليات تجميع الحليب من الاسر';
    protected static string $color = 'info';
    protected function getData(): array
    {
            $data = Trend::model(CollectingMilkFromFamily::class)
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();
     
        return [
            'datasets' => [
                [
                    'label' => 'عمليات تجميع الحليب من الاسر',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
