<?php

namespace App\Enums\Tenant;

use Filament\Support\Contracts\HasLabel;

enum TypeTenantEnum: string implements HasLabel
{
    case VEHICLES = 'vehicles';

    case MECHANICS = 'mechanics';

    case OTHERS = 'others';

    public function getLabel(): string
    {
        return match ($this) {
            self::VEHICLES => 'Veículos',
            self::MECHANICS => 'Mecânica',
            self::OTHERS => 'Outros',
        };
    }
}
