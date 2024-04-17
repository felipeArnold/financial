<?php

namespace App\Models;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;
use Leandrocfe\FilamentPtbrFormFields\Document;

class Lead extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public static function getForm(): array
    {
        return [
            Section::make('Informações do lead')
                ->description('Dados gerais do lead')
                ->schema([
                    TextInput::make('name')
                        ->label('Nome')
                        ->rules('required'),
                    Document::make('document')
                        ->label('CPF/CNPJ'),
                    TextInput::make('email')
                        ->label('E-mail')
                        ->rules([
                            'nullable',
                            'email:rfc,dns'
                        ]),
                    PhoneNumber::make('phone')
                        ->label('Telefone')
                        ->rules('required'),
                    DatePicker::make('birthday')
                        ->label('Data de nascimento')
                ])
            ->columns(2),
        ];
    }
}
