<?php

namespace App\Models;

use App\Observers\ProductObserver;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Leandrocfe\FilamentPtbrFormFields\Money;

#[ObservedBy(ProductObserver::class)]
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
                ->schema([
                    TextInput::make('name')
                        ->label('Nome')
                        ->required(),
                    Money::make('price')
                        ->label('Preço')
                        ->required(),
                    TextInput::make('description')
                        ->label('Descrição'),
                ])->columns(),
        ];
    }
}
