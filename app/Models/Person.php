<?php

namespace App\Models;

use Filament\Forms;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Leandrocfe\FilamentPtbrFormFields\Document;

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

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

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
            Forms\Components\Tabs::make('Tabs')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Dados gerais')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nome')
                                ->rules([
                                    'required',
                                    'max:50',
                                ])
                                ->required(),
                            Forms\Components\TextInput::make('surname')
                                ->label('Apelido')
                                ->rules([
                                    'nullable',
                                    'max:50',
                                ]),
                            Document::make('document')
                                ->label('CPF/CNPJ')
                                ->dynamic(),
                            Forms\Components\DatePicker::make('birth_date')
                                ->label('Data de nascimento')
                                ->native(),
                            Forms\Components\TextInput::make('nationality')
                                ->label('Nacionalidade')
                                ->rules([
                                    'nullable',
                                    'max:50',
                                ]),
                            Forms\Components\TextInput::make('naturalness')
                                ->label('Naturalidade')
                                ->rules([
                                    'nullable',
                                    'max:50',
                                ]),
                        ]),
                    Forms\Components\Tabs\Tab::make('E-mails')
                        ->icon('heroicon-o-envelope')
                        ->schema(Emails::getForm()),
                    Forms\Components\Tabs\Tab::make('Telefones')
                        ->icon('heroicon-o-phone')
                        ->schema(Phones::getForm()),
                    Forms\Components\Tabs\Tab::make('Endereços')
                        ->schema(Addresses::getForm()),
                ])
                ->persistTab()
                ->columnSpan(2),
        ];
    }
}
