<?php

namespace App\Filament\Resources\TransferToFactoryResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\TransferToFactory;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
class TransferToFactoryChart extends ChartWidget
{
    protected static ?string $heading = 'تحويل الحليب من الجمعية الى المصنع';
    protected static string $color = 'info';
    protected function getData(): array
    {
            $data = Trend::model(TransferToFactory::class)
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();
     
        return [
            'datasets' => [
                [
                    'label' => 'تحويل الحليب من الجمعية الى المصنع',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
