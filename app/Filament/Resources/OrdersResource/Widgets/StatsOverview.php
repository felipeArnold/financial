<?php

namespace App\Filament\Resources\OrdersResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
//    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        return [
            stat::make('Unique views', '500.1k')
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10])
                ->color('success'),
            stat::make('Orders', '1,200')
                ->description('200 increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([3, 2, 5, 3, 7, 4, 9])
                ->color('success'),
            stat::make('Revenue', '$12,000')
                ->description('2k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            stat::make('Conversion rate', '2.5%')
                ->description('0.5% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([3, 2, 5, 3, 7, 4, 9])
                ->color('success'),
        ];
    }
}
