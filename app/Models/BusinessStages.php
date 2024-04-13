<?php

namespace App\Models;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessStages extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    protected $attributes = [
        'order' => 0
    ];

    public function funnels(): BelongsTo
    {
        return $this->belongsTo(BusinessFunnels::class);
    }

    public static function getForm(): array
    {
        return [
            Repeater::make('stages')
                ->label('Estágios')
                ->relationship()
                ->simple(
                    TextInput::make('name')
                        ->label('Nome')
                        ->required(),
                )
                ->cloneable()
                ->reorderableWithButtons()
                ->defaultItems(2)
        ];
    }
}
