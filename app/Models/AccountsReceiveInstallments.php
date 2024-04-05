<?php

namespace App\Models;

use App\Observers\AccountsReceiveInstallmentsObserver;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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

    public function accountsReceive(): BelongsTo
    {
        return $this->belongsTo(AccountsReceive::class);
    }

    public static function getForm(): array
    {
        return [
            Repeater::make('installments')
                ->hiddenLabel()
                ->relationship()
                ->schema([
                    DatePicker::make('due_date')
                        ->label('Data de vencimento')
                        ->required(),
                    DatePicker::make('pay_date')
                        ->label('Data de pagamento'),
                    TextInput::make('document_number')
                        ->label('Número do documento')
                        ->columnSpan(2),
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
                ->collapsible()
                ->cloneable(),
            //                ->itemLabel(fn (array $state): ?string => 'Parcela ' . Str::padLeft($state['parcel'], 2, '0'))
        ];
    }
}
