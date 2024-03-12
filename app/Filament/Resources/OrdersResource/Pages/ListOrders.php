<?php

namespace App\Filament\Resources\OrdersResource\Pages;

use App\Filament\Resources\OrdersResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = OrdersResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return OrdersResource::getWidgets();
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Todos'),
            'Novos' => Tab::make()->query(fn ($query) => $query->where('status', 'new')),
            'Processando' => Tab::make()->query(fn ($query) => $query->where('status', 'processing')),
            'Enviado' => Tab::make()->query(fn ($query) => $query->where('status', 'shipped')),
            'Entregue' => Tab::make()->query(fn ($query) => $query->where('status', 'delivered')),
            'Cancelado' => Tab::make()->query(fn ($query) => $query->where('status', 'cancelled')),
        ];
    }
}
