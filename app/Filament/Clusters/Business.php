<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Business extends Cluster
{
    protected static ?string $navigationLabel = 'Configurações';

    protected static ?string $navigationGroup = 'Negócios';

    protected static ?string $navigationIcon = 'heroicon-o-cog';
}
