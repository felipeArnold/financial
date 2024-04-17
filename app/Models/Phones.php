<?php

namespace App\Models;

use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;

class Phones extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
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
            Repeater::make('phones')
                ->label('Telefones')
                ->relationship()
                ->schema([
                    PhoneNumber::make('number')
                        ->label('Telefone')
                        ->rules('required'),
                ])
                ->addActionLabel('Adicionar telefone')
                ->collapsible()
                ->cloneable()
                ->grid(2)
                ->itemLabel(fn (array $state): ?string => $state['number']),
        ];
    }
}
