<?php

namespace App\Models;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Leandrocfe\FilamentPtbrFormFields\Cep;

class Addresses extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'street',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'postal_code',
        'morphs',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public static function getForm(): array
    {
        return [
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
                ])
                ->addActionLabel('Adicionar endereço')
                ->collapsible()
                ->cloneable()
                ->grid(2)
                ->itemLabel(fn (array $state): ?string => $state['street'].', '.$state['number']),
        ];
    }
}
