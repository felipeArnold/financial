<?php

namespace App\Filament\Pages\Tenancy;

namespace App\Filament\Pages\Tenancy;

use App\Models\Tenant;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditTeamProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Editar dados da equipe';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(Tenant::getForm());
    }
}
