<?php

namespace App\Models;

use App\Filament\Forms\Components\PtbrMoney;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Leandrocfe\FilamentPtbrFormFields\Money;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
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
                        ->rules([
                            'required',
                            'max:255',
                        ])
                        ->validationMessages([
                            'name.required' => 'O campo nome é obrigatório',
                            'name.max' => 'O campo nome deve ter no máximo 255 caracteres',
                        ])
                        ->columnSpan(2),
                    Select::make('product_category_id')
                        ->label('Categoria')
                        ->options(ProductCategory::pluck('name', 'id'))
                        ->placeholder('Selecione uma categoria')
                        ->searchable()
                        ->createOptionForm(function () {
                            return ProductCategory::getForm();
                        })
                        ->createOptionUsing(function (array $data): int {
                            return ProductCategory::create([
                                 'tenant_id' => auth()->user()->tenants()->first()->id,
                                'name' => $data['name']
                            ])->id;
                        })
                        ->native(false)
                        ->rules([
                            'required',
                            'exists:product_categories,id',
                        ])
                        ->validationMessages([
                            'category.required' => 'O campo categoria é obrigatório',
                            'category.exists' => 'A categoria selecionada não existe',
                        ]),
                    TextInput::make('stock')
                        ->label('Estoque')
                        ->numeric(),
                    PtbrMoney::make('cost_price')
                        ->label('Preço de custo'),
                    PtbrMoney::make('sale_price')
                        ->label('Preço de venda'),
                    MarkdownEditor::make('description')
                        ->label('Descrição')
                        ->columnSpan(2)
                ])->columns(2),
        ];
    }
}
