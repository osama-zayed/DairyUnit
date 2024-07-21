<?php

namespace App\Filament\Resources\ReceiptInvoiceFromStoreResource\Widgets;

use Filament\Widgets\ChartWidget;

use App\Models\ReceiptInvoiceFromStore;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
class ReceiptInvoiceFromStoreChart extends ChartWidget
{
    protected static ?string $heading = 'عمليات توريد الحليب من المجمعين';
    protected static string $color = 'info';
    protected function getData(): array
    {
            $data = Trend::model(ReceiptInvoiceFromStore::class)
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
        return 'line';
    }
}
