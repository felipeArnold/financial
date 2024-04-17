<?php

namespace App\Models;

use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Leandrocfe\FilamentPtbrFormFields\Money;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
    ];


    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public static function getForm(): array
    {
        return [
            Section::make('Informações do produto')
                ->description('Preencha as informações do produto')
                ->schema([
                    TextInput::make('name')
                        ->label('Nome')
                        ->placeholder('Nome do produto')
                        ->columnSpan(['sm' => 6])
                        ->rules([
                            'required',
                            'max:255',
                        ])
                        ->validationMessages([
                            'name.required' => 'O campo nome é obrigatório',
                            'name.max' => 'O campo nome deve ter no máximo 255 caracteres',
                        ]),
                    Money::make('price')
                        ->label('Preço')
                        ->columnSpan(['sm' => 6]),
                    MarkdownEditor::make('description')
                        ->label('Descrição')
                        ->columnSpan(['sm' => 12]),
                ])->columns(),
        ];
    }
}
