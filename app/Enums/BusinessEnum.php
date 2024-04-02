<?php

namespace App\Enums;

use Mokhosh\FilamentKanban\Concerns\IsKanbanStatus;

enum BusinessEnum: string
{
    use IsKanbanStatus;

    case MISSING = 'missing';
    case GAIN = 'gain';
    case RUNNING = 'running';
    case PENDING = 'pending';

    public function getTitle(): string
    {
        return match ($this) {
            self::MISSING => 'Em atraso',
            self::GAIN => 'Ganho',
            self::RUNNING => 'Em andamento',
            self::PENDING => 'Pendente',
        };
    }
}
