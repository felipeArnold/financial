<?php

namespace App\Models;

use App\Enums\AccountsReceive\TypePaymentEnum;
use App\Observers\AccountsReceiveInstallmentsObserver;
use Carbon\Carbon;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
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
            Section::make('Dados da parcela')
            ->columns(2)
            ->schema([
                Money::make('amount')
                    ->label('Valor total')
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $set('installments', collect($get('installments'))->map(function ($installment) use ($state, $get) {
                            return array_merge($installment, [
                                'value' => $state / $get('parcels'),
                            ]);
                        })->toArray());
                    })
                    ->required()
                    ->default(0),
                Select::make('parcels')
                    ->label('Parcelas')
                    ->required()
                    ->options(fn () => collect(range(1, 99))->mapWithKeys(fn ($value) => [$value => $value]))
                    ->native()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        $installments = [];

                        for ($i = 1; $i <= $state; $i++) {
                            $installments[] = [
                                'parcel' => $i,
                                'due_date' => now()->addMonth($i)->format('Y-m-d'),
                                'pay_date' => null,
                                'type' => TypePaymentEnum::bank_slip,
                                'document_number' => null,
                                'value' => 0,
                                'discount' => 0,
                                'interest' => 0,
                                'fine' => 0,
                                'value_paid' => 0,
                                'status' => 'open',
                                'observation' => null,
                            ];
                        }

                        $set('installments', $installments);
                    })
                    ->default(1),
            ]),
            Repeater::make('installments')
                ->hiddenLabel()
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
                        ->default(TypePaymentEnum::bank_slip),
                    TextInput::make('document_number')
                        ->label('Número do documento'),
                    Money::make('value')
                        ->label('Valor')
                        ->disabled()
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
                        ->native()
                        ->default('open')
                        ->required(),
                    FileUpload::make('file')
                        ->label('Anexo')
                        ->acceptedFileTypes(['application/pdf'])
                        ->rules(['file', 'max:1024'])
                        ->preserveFilenames()
                        ->openable()
                        ->downloadable(),
                    MarkdownEditor::make('observation')
                        ->label('Observação'),
                ])
                ->columns(2)
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
