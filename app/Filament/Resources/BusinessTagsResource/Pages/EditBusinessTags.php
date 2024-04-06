<?php

namespace App\Filament\Resources\BusinessTagsResource\Pages;

use App\Filament\Resources\BusinessTagsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBusinessTags extends EditRecord
{
    protected static string $resource = BusinessTagsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
