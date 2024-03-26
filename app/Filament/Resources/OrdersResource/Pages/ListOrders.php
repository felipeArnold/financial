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
            null => Tab::make('Todos')
                ->icon('heroicon-o-rectangle-stack'),
            'Orçamento' => Tab::make()
                ->icon('heroicon-o-document')
                ->query(fn ($query) => $query->where('status', 'budget')),
            'Aberto' => Tab::make()
                ->icon('heroicon-o-document-duplicate')
                ->query(fn ($query) => $query->where('status', 'open')),
            'Em andamento' => Tab::make()
                ->icon('heroicon-o-cog')
                ->query(fn ($query) => $query->where('status', 'progress')),
            'Finalizado' => Tab::make()
                ->icon('heroicon-o-check-circle')
                ->query(fn ($query) => $query->where('status', 'finished')),
            'Cancelado' => Tab::make()
                ->icon('heroicon-o-x-circle')
                ->query(fn ($query) => $query->where('status', 'canceled')),
            'Aguardando' => Tab::make()
                ->icon('heroicon-o-clock')
                ->query(fn ($query) => $query->where('status', 'waiting')),
            'Aprovado' => Tab::make()
                ->icon('heroicon-o-check')
                ->query(fn ($query) => $query->where('status', 'approved')),
        ];
    }
}
