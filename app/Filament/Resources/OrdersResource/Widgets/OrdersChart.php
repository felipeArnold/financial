<?php

namespace App\Filament\Resources\OrdersResource\Widgets;

use Filament\Widgets\ChartWidget;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Ordens por mês';

    protected static ?int $sort = 1;

    protected function getType(): string
    {
        return 'bar';
    }

    public function getDescription(): ?string
    {
        return 'Ordens criadas por mês';
    }

    protected function getData(): array
    {
        $orders = \App\Models\Orders::all()->groupBy(function ($order) {
            return $order->created_at->format('m');
        });

        return [
            'datasets' => [
                [
                    'label' => 'Serviços',
                    'data' => $orders->map(function ($orders) {
                        return $orders->count();
                    })->toArray(),
                    'fill' => 'start',
                ],
            ],
            'labels' => ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
        ];
    }
}
