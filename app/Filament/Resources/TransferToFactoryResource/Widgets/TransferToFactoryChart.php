<?php

namespace App\Filament\Resources\TransferToFactoryResource\Widgets;

use Filament\Widgets\ChartWidget;

class TransferToFactoryChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
