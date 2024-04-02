<?php

namespace App\Models;

use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Leandrocfe\FilamentPtbrFormFields\Money;

class AccountsReceive extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(AccountsReceiveInstallments::class);
    }

    public static function getForm(): array
    {
        return [
            Section::make('Informações do gerais')
                ->schema([

                    Select::make('person_id')
                        ->label('Cliente')
                        ->options(Person::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->native(false),
                    Select::make('user_id')
                        ->label('Responsável')
                        ->options(User::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->native(false),
                    TextInput::make('title')
                        ->label('Título')
                        ->required()
                    ->columnSpan(2),
                   MarkdownEditor::make('observation')
                        ->label('Observação')
                       ->columnSpan(2)
                ])->columns(),
        ];
    }
}
