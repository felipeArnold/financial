<?php

namespace App\Models;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Leandrocfe\FilamentPtbrFormFields\Money;

class BusinessTags extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public static function getForm(): array
    {
        return [
            Section::make('Informações gerais')
                ->description('Preencha as informações da tag')
                ->schema([
                    TextInput::make('name')
                        ->label('Nome')
                        ->placeholder('Nome do produto')
                        ->columnSpan(1)
                        ->rules([
                            'required',
                            'max:255',
                        ])
                        ->validationMessages([
                            'name.required' => 'O campo nome é obrigatório',
                            'name.max' => 'O campo nome deve ter no máximo 255 caracteres',
                        ]),
                    ColorPicker::make('color')
                        ->label('Cor')
                        ->columnSpan(1),
                ])->columns(),
        ];
    }
}
