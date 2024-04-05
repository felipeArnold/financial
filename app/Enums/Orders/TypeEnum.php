<?php

namespace App\Enums\Orders;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TypeEnum: string implements HasColor, HasIcon, HasLabel
{
    case service = 'service';
    case sale = 'sale';

    public function getLabel(): string
    {
        return match ($this) {
            self::service => 'Serviço',
            self::sale => 'Venda',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::service => 'primary',
            self::sale => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::service => 'heroicon-o-cog',
            self::sale => 'heroicon-o-shopping-cart',
        };
    }
}
