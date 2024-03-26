<?php

namespace App\Models;

use App\Observers\PersonObserver;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Leandrocfe\FilamentPtbrFormFields\Document;
use Filament\Forms;

#[ObservedBy(PersonObserver::class)]
class Person extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'birth_date' => 'date',
    ];

    public function phones(): MorphMany
    {
        return $this->morphMany(Phones::class, 'phonable');
    }

    public function addresses(): MorphMany
    {
        return $this->morphMany(Addresses::class, 'addressable');
    }

    public function emails(): MorphMany
    {
        return $this->morphMany(Emails::class, 'emailable');
    }

    public static function getForm(): array
    {
        return [
            Section::make('Dados gerais')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nome')
                        ->rules([
                            'required',
                            'max:50'
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('surname')
                        ->label('Apelido')
                        ->rules([
                            'nullable',
                            'max:50'
                        ]),
                    Document::make('document')
                        ->label('CPF/CNPJ')
                        ->dynamic()
                        ->required(),
                    Forms\Components\DatePicker::make('birth_date')
                        ->label('Data de nascimento')
                        ->required(),
                    Forms\Components\TextInput::make('nationality')
                        ->label('Nacionalidade')
                        ->rules([
                            'nullable',
                            'max:50'
                        ]),
                    Forms\Components\TextInput::make('naturalness')
                        ->label('Naturalidade')
                        ->rules([
                            'nullable',
                            'max:50'
                        ]),
                ])->columns(),
        ];
    }
}
