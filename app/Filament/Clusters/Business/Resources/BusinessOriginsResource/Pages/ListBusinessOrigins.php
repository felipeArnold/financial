<?php

namespace App\Filament\Clusters\Business\Resources\BusinessOriginsResource\Pages;

use App\Filament\Clusters\Business\Resources\BusinessOriginsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinessOrigins extends ListRecords
{
    protected static string $resource = BusinessOriginsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
