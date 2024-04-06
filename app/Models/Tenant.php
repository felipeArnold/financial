<?php

namespace App\Models;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;
use Leandrocfe\FilamentPtbrFormFields\Cep;
use Leandrocfe\FilamentPtbrFormFields\Document;

class Tenant extends Model
{
    //    use HasFactory;

    protected $guarded = ['id'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function people(): HasMany
    {
        return $this->hasMany(Person::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Orders::class);
    }

    public function accountsReceives(): HasMany
    {
        return $this->hasMany(AccountsReceive::class);
    }

    public function businessTags(): HasMany
    {
        return $this->hasMany(BusinessTags::class);
    }

    public function businessOrigins(): HasMany
    {
        return $this->hasMany(BusinessOrigins::class);
    }

    public function addresses(): MorphMany
    {
        return $this->morphMany(Addresses::class, 'addressable');
    }


    public function getFilamentAvatarUrl(): ?string
    {
        return Storage::url($this->avatar);
    }

    public static function getForm(): array
    {
        return [
            Section::make('Informações da empresa')
                ->description('Dados gerais da empresa.')
                ->schema([
                    Split::make([
                        Section::make([
                            TextInput::make('name')
                                ->label('Nome')
                                ->required(),
                            Document::make('document')
                                ->label('CPF/CNPJ')
                                ->required(),
                            FileUpload::make('avatar')
                                ->label('Avatar')
                                ->acceptedFileTypes(['image/*'])
                                ->rules(['image', 'max:1024']),
                        ])->grow(false),
                        Section::make([
                            Repeater::make('addresses')
                                ->label('Endereços')
                                ->relationship()
                                ->schema([
                                    Cep::make('postal_code')
                                        ->label('CEP')
                                        ->live(onBlur: true)
                                        ->viaCep(
                                            mode: 'suffix',
                                            errorMessage: 'CEP inválido.',
                                            setFields: [
                                                'street' => 'logradouro',
                                                'number' => 'numero',
                                                'complement' => 'complemento',
                                                'district' => 'bairro',
                                                'city' => 'localidade',
                                                'state' => 'uf',
                                            ]
                                        )
                                        ->required(),
                                    TextInput::make('street')
                                        ->label('Rua')
                                        ->required(),
                                    TextInput::make('number')
                                        ->label('Número')
                                        ->required(),
                                    TextInput::make('complement')
                                        ->label('Complemento'),
                                    TextInput::make('district')
                                        ->label('Bairro')
                                        ->required(),
                                    TextInput::make('city')
                                        ->label('Cidade')
                                        ->required(),
                                    TextInput::make('state')
                                        ->label('Estado')
                                        ->required(),
                                ])->columns()
                                ->itemLabel(fn (array $state): ?string => $state['street'].', '.$state['number'])
                                ->cloneable()
                                ->collapsed(),
                        ]),
                    ])->from('md'),
                ]),

        ];
    }
}
