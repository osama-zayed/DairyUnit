<?php

namespace App\Filament\Resources\CollectingMilkFromFamilyResource\Widgets;

use Filament\Widgets\ChartWidget;

class CollectingaChart extends ChartWidget
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
