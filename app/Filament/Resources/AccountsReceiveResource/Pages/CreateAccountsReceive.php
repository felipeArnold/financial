<?php

namespace App\Filament\Resources\AccountsReceiveResource\Pages;

use App\Filament\Resources\AccountsReceiveResource;
use App\Models\AccountsReceive;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAccountsReceive extends CreateRecord
{
    protected static string $resource = AccountsReceiveResource::class;
}
