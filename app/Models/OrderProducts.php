<?php

namespace App\Models;

use App\Filament\Forms\Components\PtbrMoney;
use App\Helpers\FormatterHelper;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
    protected $guarded = ['id'];

    public static function getForm(): array
    {
        return [
            Repeater::make('products')
                ->label('Produtos')
                ->relationship('products')
                ->schema([
                    TextInput::make('description')
                        ->label('Descrição')
                        ->rules('required'),
                    TextInput::make('quantity')
                        ->label('Quantidade')
                        ->type('number')
                        ->default(1)
                        ->rules('required', 'integer')
                        ->reactive()
                        ->minValue(1)
                        ->afterStateUpdated(function ($state, $set, $get) {

                            if (is_null($get('price')) || $get('price') == '') {
                                return;
                            }

                            if (is_null($get('quantity')) || $get('quantity') == '') {
                                return;
                            }

                            $quantity = $get('quantity') ?? 0;
                            $price = FormatterHelper::decimal($get('price') ?? 0);

                            $set('total', FormatterHelper::money($quantity * $price));
                        }),
                    PtbrMoney::make('price')
                        ->label('Preço')
                        ->rules('required')
                        ->reactive()
                        ->afterStateUpdated(function ($state, $set, $get) {
                            if (is_null($get('price')) || $get('price') == '') {
                                return;
                            }

                            if (is_null($get('quantity')) || $get('quantity') == '') {
                                return;
                            }

                            $quantity = $get('quantity') ?? 0;
                            $price = FormatterHelper::decimal($get('price') ?? 0);

                            $set('total', FormatterHelper::money($quantity * $price));
                        }),
                    PtbrMoney::make('total')
                        ->label('Total')
                        ->rules('required'),
                ])
                ->columns(2)
                ->addActionLabel('Adicionar produto')
                ->cloneable()
                ->collapsible()
                ->itemLabel(fn (array $state): ?string => $state['description']),
        ];
    }
}
