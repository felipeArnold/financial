<?php

namespace App\Filament\Resources\BusinessTagsResource\Pages;

use App\Filament\Resources\BusinessTagsResource;
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
