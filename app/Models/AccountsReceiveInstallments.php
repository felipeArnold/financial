<?php

namespace App\Models;

use App\Enums\AccountsReceive\TypePaymentEnum;
use App\Observers\AccountsReceiveInstallmentsObserver;
use Carbon\Carbon;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Leandrocfe\FilamentPtbrFormFields\Money;

#[ObservedBy(AccountsReceiveInstallmentsObserver::class)]
class AccountsReceiveInstallments extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'due_date' => 'date',
        'pay_date' => 'date',
        'value' => 'decimal:2',
        'discount' => 'decimal:2',
        'interest' => 'decimal:2',
        'fine' => 'decimal:2',
        'value_paid' => 'decimal:2',
        'type' => TypePaymentEnum::class,
    ];

    public function accountsReceive(): BelongsTo
    {
        return $this->belongsTo(AccountsReceive::class);
    }

    public static function getForm(): array
    {
        return [
            Repeater::make('installments')
                ->defaultItems(1)
                ->addActionLabel('Adicionar parcela')
                ->relationship()
                ->schema([
                    DatePicker::make('due_date')
                        ->label('Data de vencimento')
                        ->required(),
                    DatePicker::make('pay_date')
                        ->label('Data de pagamento'),
                    Select::make('type')
                        ->label('Tipo de pagamento')
                        ->options(TypePaymentEnum::class)
                        ->required()
                        ->columnSpan(1),
                    TextInput::make('document_number')
                        ->label('Número do documento')
                        ->columnSpan(1),
                    Money::make('value')
                        ->label('Valor')
                        ->required(),
                    Money::make('discount')
                        ->label('Desconto'),
                    Money::make('interest')
                        ->label('Juros'),
                    Money::make('fine')
                        ->label('Multa'),
                    Money::make('value_paid')
                        ->label('Valor pago'),
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'open' => 'Aberto',
                            'paid' => 'Pago',
                            'canceled' => 'Cancelado',
                        ])
                        ->default('open')
                        ->required(),
                    MarkdownEditor::make('observation')
                        ->label('Observação')
                        ->columnSpan(2),

                ])
                ->itemLabel(fn (array $state): ?string => $state['status'] === 'open' ? 'Parcela em aberto' : 'Parcela paga')
                ->collapsible()
                ->cloneable()
                ->extraItemActions([
                    Action::make('duplicate')
                        ->label('Duplicar')
                        ->icon('heroicon-o-document-duplicate')
                        ->action(
                            function ($record) {
                                return $record->replicate()->save();
                            }
                        ),
                   Action::make('pay')
//                        ->disabled(fn ($record) => $record->status !== 'open')
                        ->icon('heroicon-o-check-circle')
                        ->label('Marcar como pago')
                        ->requiresConfirmation()
                        ->action(
                            function ($record) {

                                $record->update([
                                    'status' => 'paid',
                                    'pay_date' => now(),
                                    'value_paid' => $record->value,
                                ]);

                                Notification::make()
                                    ->title('Parcela paga')
                                    ->body('A parcela foi marcada como paga com sucesso.')
                                    ->success()
                                    ->seconds(3)
                                    ->send();
                            }
                        ),
                    Action::make('pay_and_create_another')
//                        ->disabled(fn ($record) => $record->status !== 'open')
                        ->icon('heroicon-o-check-circle')
                        ->label('Marcar como pago e criar outra')
                        ->requiresConfirmation()
                        ->action(
                            function ($record) {

                                $record->update([
                                    'status' => 'paid',
                                    'pay_date' => now(),
                                    'value_paid' => $record->value,
                                ]);

                                AccountsReceiveInstallments::create([
                                    'accounts_receive_id' => $record->accounts_receive_id,
                                    'parcel' => $record->parcel + 1,
                                    'due_date' => Carbon::parse($record->due_date)->addMonth(),
                                    'pay_date' => null,
                                    'value' => $record->value,
                                    'discount' => $record->discount,
                                    'interest' => $record->interest,
                                    'fine' => $record->fine,
                                    'value_paid' => $record->value_paid,
                                    'status' => 'open',
                                    'observation' => $record->observation,
                                ]);

                                Notification::make()
                                    ->title('Parcela paga')
                                    ->body('A parcela foi marcada como paga com sucesso.')
                                    ->success()
                                    ->seconds(3)
                                    ->send();
                            }
                        ),
                ]),
        ];
    }
}
