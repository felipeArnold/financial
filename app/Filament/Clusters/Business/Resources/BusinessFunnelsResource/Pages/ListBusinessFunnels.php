<?php

namespace App\Filament\Clusters\Business\Resources\BusinessFunnelsResource\Pages;

use App\Filament\Clusters\Business\Resources\BusinessFunnelsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinessFunnels extends ListRecords
{
    protected static string $resource = BusinessFunnelsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
