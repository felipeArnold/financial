<?php

namespace App\Filament\Clusters\Business\Resources\BusinessTagsResource\Pages;

use App\Filament\Clusters\Business\Resources\BusinessTagsResource;
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
