<?php

namespace App\Filament\Resources\VehiclesResource\Pages;

use App\Filament\Resources\VehiclesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVehicles extends EditRecord
{
    protected static string $resource = VehiclesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
