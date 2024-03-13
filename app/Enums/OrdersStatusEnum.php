<?php

namespace App\Enums;


enum OrdersStatusEnum: string
{
    case new = 'new';
    case processing = 'processing';
    case shipped = 'shipped';
    case delivered = 'delivered';
    case cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::new => 'Novo',
            self::processing => 'Processando',
            self::shipped => 'Enviado',
            self::delivered => 'Entregue',
            self::cancelled => 'Cancelado',
        };
    }
}
