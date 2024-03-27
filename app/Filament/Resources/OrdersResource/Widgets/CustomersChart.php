<?php

namespace App\Filament\Resources\OrdersResource\Widgets;

use App\Models\Orders;
use Filament\Widgets\ChartWidget;

class CustomersChart extends ChartWidget
{
    protected static ?string $heading = 'Valor ordens';

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {

        $ordens = Orders::query()
            ->get()
            ->groupBy(function ($order) {
                return $order->created_at->format('m');
            });

        return [
            'datasets' => [
                [
                    'label' => 'Ordens',
                    'data' => $ordens->map(function ($orders) {
                        return $orders->sum('total');
                    })->toArray(),
                    'fill' => 'start',
                ],
            ],
            'labels' => ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
        ];
    }
}
