<?php

namespace App\Enums\Orders;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StatusEnum: string implements HasColor, HasIcon, HasLabel
{
    case pending = 'budget';
    case open = 'open';
    case progress = 'progress';
    case finished = 'finished';
    case canceled = 'canceled';
    case waiting = 'waiting';
    case approved = 'approved';

    public function getLabel(): string
    {
        return match ($this) {
            self::pending => 'Orçamento',
            self::open => 'Aberto',
            self::progress => 'Em andamento',
            self::finished => 'Finalizado',
            self::canceled => 'Cancelado',
            self::waiting => 'Aguardando',
            self::approved => 'Aprovado',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::pending => 'primary',
            self::open => 'info',
            self::progress => 'warning',
            self::finished => 'success',
            self::canceled => 'danger',
            self::waiting => 'warning',
            self::approved => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::pending => 'heroicon-o-document',
            self::open => 'heroicon-o-document-duplicate',
            self::progress => 'heroicon-o-cog',
            self::finished => 'heroicon-o-check-circle',
            self::canceled => 'heroicon-o-x-circle',
            self::waiting => 'heroicon-o-clock',
            self::approved => 'heroicon-o-check',
        };
    }
}
