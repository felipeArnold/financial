<?php

namespace App\Filament\Resources\OrdersResource\Pages;

use App\Enums\Orders\StatusEnum;
use App\Filament\Resources\OrdersResource;
use App\Models\AccountsReceive;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewOrders extends ViewRecord
{
    protected static string $resource = OrdersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->disabled(fn ($record) => $record->status === StatusEnum::approved),

            Actions\ActionGroup::make([

                Actions\Action::make('generate_account_receivable')
                        ->label('Gerar contas a receber')
                        ->icon('heroicon-o-currency-dollar')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $accountReceivable = AccountsReceive::create([
                                'tenant_id' => $record->tenant_id,
                                'person_id' => $record->person_id,
                                'user_id' => $record->user_id,
                                'description' => $record->description,
                                'title' => $record->order_number,
                                'status' => 'open',
                            ]);

                            $accountReceivable->installments()->create([
                                'document_number' => $record->order_number,
                                'value' => $record->total,
                                'due_date' => $record->initial_date,
                                'status' => 'open',
                            ]);

                            $record->update(['status' => 'approved']);

                            return Notification::make()
                                ->title('Contas a receber geradas')
                                ->body('As contas a receber foram geradas com sucesso')
                                ->success()
                                ->seconds(3)
                                ->send();
                        }),
                Actions\DeleteAction::make()
                    ->disabled(fn ($record) => $record->status === StatusEnum::approved),
            ]),
        ];
    }
}
