<?php

namespace App\Filament\Resources\OrdersResource\Widgets;

use App\Models\Orders;
use App\Models\Person;
use App\Models\Vehicles\Vehicles;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    //    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        // query orders by period and count orders days
        $orders = Orders::query()
            ->selectRaw('count(*) as count, date(created_at) as date, sum(total) as total')
            ->groupBy('date')
            ->get();

        $clients = Person::query()
            ->where('type', 'P')
            ->get();

        $vehicles = Vehicles::query()
            ->orderBy('sale_date')
            ->get()
            ->groupBy(function ($date) {
                return $date->sale_date->format('m');
            });

        return [
            stat::make('Clientes', $clients->count())
                ->description('Clientes cadastrados')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($clients->pluck('id')->toArray())
                ->color('success'),
            stat::make('Ordens', $orders->pluck('total')->sum())
                ->description($orders->count().' criadas')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($orders->pluck('count')->toArray())
                ->color('success'),
            stat::make('Veículos', $vehicles->count())
                ->description('Veículos vendidos')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($vehicles->pluck('id')->toArray())
                ->color('success'),
            stat::make('Receita', $orders->pluck('total')->sum())
                ->description('Receita total')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($orders->pluck('total')->toArray())
                ->color('success'),
        ];
    }
}
