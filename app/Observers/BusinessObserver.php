<?php

namespace App\Observers;

use App\Models\Business;
use App\Models\User;
use Filament\Notifications\Notification;

class BusinessObserver
{
    /**
     * Handle the Business "created" event.
     */
    public function created(Business $business): void
    {
        $user = User::find($business->responsible_id);

        Notification::make()
            ->title('Nova negociação criada')
            ->body('Você foi atribuído a uma nova negociação.')
            ->success()
            ->sendToDatabase($user);
    }

    /**
     * Handle the Business "updated" event.
     */
    public function updated(Business $business): void
    {
        $user = User::find($business->responsible_id);

        Notification::make()
            ->title('Negociação atualizada')
            ->body('Você foi atribuído a uma negociação atualizada.')
            ->success()
            ->sendToDatabase($user);
    }


}
