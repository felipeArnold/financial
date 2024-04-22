<?php

namespace App\Enums\Tenant;

use Filament\Support\Contracts\HasLabel;

enum PlansEnum: string implements HasLabel
{
    case TEST = 'test';
    case FREE = 'free';
    case PREMIUM = 'premium';

    public function getLabel(): string
    {
        return match ($this) {
            self::TEST => 'Test',
            self::FREE => 'Free',
            self::PREMIUM => 'Premium',
        };
    }
}
