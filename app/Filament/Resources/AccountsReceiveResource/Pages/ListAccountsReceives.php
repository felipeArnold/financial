<?php

namespace App\Filament\Resources\AccountsReceiveResource\Pages;

use App\Filament\Resources\AccountsReceiveResource;
use App\Filament\Resources\OrdersResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListAccountsReceives extends ListRecords
{
    protected static string $resource = AccountsReceiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Todos')
                ->icon('heroicon-o-rectangle-stack'),
            'Em aberto' => Tab::make()
                ->icon('heroicon-o-document')
                ->query(function ($query) {
                    $query->where('status', 'open');
                }),
            'Pago' => Tab::make()
                ->icon('heroicon-o-check-circle')
                ->query(function ($query) {
                    $query->where('status', 'paid');
                }),
            'Cancelado' => Tab::make()
                ->icon('heroicon-o-x-circle')
                ->query(function ($query) {
                    $query->where('status', 'canceled');
                }),
        ];
    }

}
