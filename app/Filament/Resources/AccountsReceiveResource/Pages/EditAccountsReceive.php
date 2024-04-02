<?php

namespace App\Filament\Resources\AccountsReceiveResource\Pages;

use App\Filament\Resources\AccountsReceiveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccountsReceive extends EditRecord
{
    protected static string $resource = AccountsReceiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
