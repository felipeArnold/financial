<?php

namespace App\Enums\AccountsReceive;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TypePaymentEnum: string implements HasColor, HasLabel
{
    case credit_card = 'credit_card';
    case bank_slip = 'bank_slip';
    case pix = 'pix';
    case transfer = 'transfer';
    case deposit = 'deposit';

    public function getLabel(): string
    {
        return match ($this) {
            self::credit_card => 'Cartão de Crédito',
            self::bank_slip => 'Boleto Bancário',
            self::pix => 'PIX',
            self::transfer => 'Transferência',
            self::deposit => 'Depósito',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::credit_card => 'primary',
            self::bank_slip => 'info',
            self::pix => 'warning',
            self::transfer => 'success',
            self::deposit => 'danger',
        };
    }
}
