<?php

namespace App\Filament\Resources\ReceiptFromAssociationResource\Widgets;

use App\Models\ReceiptFromAssociation;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ReceiptFromAssociationChart extends ChartWidget
{
    protected static ?string $heading = 'استلام الحليب من الجمعية الى المصنع';
    protected static string $color = 'info';
    protected function getData(): array
    {
        $data = Trend::model(ReceiptFromAssociation::class)
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'عمليات توريد الحليب من المجمعين',
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
