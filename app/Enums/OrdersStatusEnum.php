<?php

namespace App\Enums;

enum OrdersStatusEnum: string
{
    case BUDGET = 'budget';
    case OPEN = 'open';
    case PROGRESS = 'progress';
    case FINISHED = 'finished';
    case CANCELED = 'canceled';
    case WAITING = 'waiting';
    case APPROVED = 'approved';

    public function getLabel(): string
    {
        return match ($this) {
            self::BUDGET => 'Orçamento',
            self::OPEN => 'Aberto',
            self::PROGRESS => 'Em andamento',
            self::FINISHED => 'Finalizado',
            self::CANCELED => 'Cancelado',
            self::WAITING => 'Aguardando',
            self::APPROVED => 'Aprovado',
        };
    }
}
