<?php

namespace App\Filament\Pages\Tenancy;

use App\Enums\Tenant\PlansEnum;
use App\Enums\Tenant\TypeTenantEnum;
use App\Models\Tenant;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Leandrocfe\FilamentPtbrFormFields\Document;

class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Cadastro de Empresa';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                Document::make('document')
                    ->label('CPF/CNPJ')
                    ->required(),
                ToggleButtons::make('type')
                    ->label('Tipo')
                    ->inline()
                    ->default(TypeTenantEnum::OTHERS)
                    ->options([
                        TypeTenantEnum::VEHICLES->value => TypeTenantEnum::VEHICLES->getLabel(),
                        TypeTenantEnum::MECHANICS->value => TypeTenantEnum::MECHANICS->getLabel(),
                        TypeTenantEnum::OTHERS->value => TypeTenantEnum::OTHERS->getLabel(),
                    ]),
                ToggleButtons::make('plans')
                    ->label('Plano')
                    ->inline()
                    ->default(PlansEnum::FREE)
                    ->options([
                        PlansEnum::TEST->value => PlansEnum::TEST->getLabel(),
                        PlansEnum::FREE->value => PlansEnum::FREE->getLabel(),
                        PlansEnum::PREMIUM->value => PlansEnum::PREMIUM->getLabel(),
                    ]),
                FileUpload::make('avatar')
                    ->label('Avatar')
                    ->acceptedFileTypes(['image/*'])
                    ->rules(['image', 'max:1024']),
            ]);
    }

    protected function handleRegistration(array $data): Tenant
    {
        $team = Tenant::create($data);

        $team->members()->attach(auth()->user());

        return $team;
    }
}
