<?php

namespace App\Filament\Resources\OrdersResource\Widgets;

use App\Models\Orders;
use App\Models\Vehicles\Vehicles;
use Filament\Widgets\ChartWidget;

class CustomersChart extends ChartWidget
{
    protected static ?string $heading = 'Veículos vendidos';

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'bar';
    }

    public function getDescription(): ?string
    {
        return 'Veículos vendidos por mês';
    }

    protected function getData(): array
    {

        $vehicles = Vehicles::query()
            ->orderBy('sale_date')
            ->get()
            ->groupBy(function ($date) {
                return $date->sale_date->format('m');
            });

        return [
            'datasets' => [
                [
                    'label' => 'Receita',
                    'data' => $vehicles->map(function ($orders) {
                        return $orders->sum('price_sale');
                    })->toArray(),
                    'tension' => 0.1,
                ],
                [
                    'label' => 'Lucro',
                    'data' => $vehicles->map(function ($orders) {
                        return $orders->sum('price_sale') - $orders->sum('purchase_price');
                    })->toArray(),
                    'tension' => 0.1,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                ],
                [
                    'label' => 'Veículos vendidos',
                    'data' => $vehicles->map(function ($orders) {
                        return $orders->count();
                    })->toArray(),
                    'tension' => 0.1,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                ]
            ],
            'labels' => ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
        ];
    }
}
