<?php

namespace App\Models;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessFunnels extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $attributes = [
        'order' => 0,
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function stages(): HasMany
    {
        return $this->hasMany(BusinessStages::class);
    }

    public static function getForm(): array
    {
        return [
            Section::make('Informações do funil')
                ->description('Preencha as informações do funil')
                ->schema([
                    TextInput::make('name')
                        ->label('Nome')
                        ->placeholder('Nome do funil')
                        ->columnSpan(['sm' => 6])
                        ->rules([
                            'required',
                            'max:255',
                        ])
                        ->validationMessages([
                            'name.required' => 'O campo nome é obrigatório',
                            'name.max' => 'O campo nome deve ter no máximo 255 caracteres',
                        ]),
                ])->columns(2),
            Section::make('Informações dos estágios')
                ->description('Preencha as informações do estágios')
                ->schema(BusinessStages::getForm()),
        ];
    }
}
