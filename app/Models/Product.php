<?php

namespace App\Models;

use App\Models\Scopes\CostumerScope;
use App\Observers\ProductObserver;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Leandrocfe\FilamentPtbrFormFields\Money;

#[ObservedBy(ProductObserver::class)]
#[ScopedBy(CostumerScope::class)]
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
    ];

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
