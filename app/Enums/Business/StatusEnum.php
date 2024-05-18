<?php

namespace App\Enums\Business;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StatusEnum: string implements HasLabel, HasColor
{
    case GAIN = 'gain';

    case RUNNING = 'running';

    case PENDING = 'pending';

    public function getLabel(): string
    {
        return match ($this) {
            self::GAIN => 'Ganho',
            self::RUNNING => 'Em andamento',
            self::PENDING => 'Pendente',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::GAIN => 'green',
            self::RUNNING => 'blue',
            self::PENDING => 'yellow',
        };
    }

}
