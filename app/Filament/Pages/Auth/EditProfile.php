<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form

            ->schema([
                Section::make('Informações do usuário')
                    ->description('Atualize suas informações pessoais.')
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        FileUpload::make('avatar')
                            ->label('Avatar')
                            ->acceptedFileTypes(['image/*'])
                            ->rules(['image', 'max:1024']),
                    ]),
            ]);
    }
}
