<?php

namespace App\Enums\Vehicles;

use Filament\Support\Contracts\HasLabel;

enum VehiclesTypeEnum: string implements HasLabel
{
    case CAR = 'car';

    case MOTORCYCLE = 'motorcycle';

    case TRUCK = 'truck';


    public function getLabel(): string
    {
        return match ($this) {
            self::CAR => 'Carro',
            self::MOTORCYCLE => 'Moto',
            self::TRUCK => 'Caminhão',
        };
    }


}
