<?php

namespace App\Models;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address',
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
            Repeater::make('emails')
                ->label('E-mails')
                ->relationship()
                ->schema([
                    TextInput::make('address')
                        ->label('E-mail')
                        ->email()
                        ->rules([
                            'required',
                            'max:50',
                        ]),
                ])
                ->addActionLabel('Adicionar e-mail')
                ->collapsible()
                ->cloneable()
                ->grid(2)
                ->itemLabel(fn (array $state): ?string => $state['address']),
        ];
    }
}
