<?php

namespace App\Models\Vehicles;

use App\Enums\Vehicles\VehiclesTypeEnum;
use App\Filament\Forms\Components\PtbrMoney;
use App\Models\Files;
use App\Models\Person;
use App\Models\Tenant;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicles extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => VehiclesTypeEnum::class,
        'sale_date' => 'date',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'owner_id');
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(VehiclesModels::class, 'model_id');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(Files::class, 'fileable');
    }

    public static function getForm(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('Dados gerais')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        ToggleButtons::make('type')
                            ->label('Tipo')
                            ->inline()
                            ->options(VehiclesTypeEnum::class)
                            ->required()
                            ->default(VehiclesTypeEnum::CAR),
                        Select::make('owner_id')
                            ->label('Proprietário')
                            ->options(Person::pluck('name', 'id'))
                            ->native(false),
                        Select::make('model_id')
                            ->label('Modelo')
                            ->options(VehiclesModels::pluck('name', 'id'))
                            ->rules('required')
                            ->native(false),
                        TextInput::make('plate')
                            ->label('Placa')
                            ->rules('required'),
                        TextInput::make('year')
                            ->label('Ano')
                            ->rules('required'),
                        TextInput::make('mileage')
                            ->label('Quilometragem')
                            ->rules('required'),
                        PtbrMoney::make('purchase_price')
                            ->label('Preço de compra'),
                        PtbrMoney::make('price_sale')
                            ->label('Preço de venda'),
                        DatePicker::make('purchase_date')
                            ->label('Data da compra'),
                        DatePicker::make('sale_date')
                            ->label('Data da venda'),
                    ])
                    ->columns(2),
                Wizard\Step::make('Arquivos')
                    ->schema(Files::getForm()),
            ])
                ->skippable()
                ->columnSpan(2)
        ];
    }
}
