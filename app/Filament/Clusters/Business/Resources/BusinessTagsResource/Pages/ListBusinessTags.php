<?php

namespace App\Filament\Clusters\Business\Resources\BusinessTagsResource\Pages;

use App\Filament\Clusters\Business\Resources\BusinessTagsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinessTags extends ListRecords
{
    protected static string $resource = BusinessTagsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
