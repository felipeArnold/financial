<?php

namespace App\Models;

use App\Enums\Business\StatusEnum;
use App\Enums\BusinessEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Leandrocfe\FilamentPtbrFormFields\Money;

class Business extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => BusinessEnum::class,
        'closing_forecast' => 'datetime',
        'closing_date' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public static function getForm(): array
    {
        return [
            Section::make('Lead')
                ->schema([
                    Select::make('people_id')
                        ->label('Pessoa')
                        ->options(Person::pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->createOptionForm(function () {
                            return Lead::getForm();
                        })
                        ->createOptionUsing(function (array $data): int {
                            return Lead::create($data)->id;
                        })
                        ->native(false)
                        ->preload(),
                    Select::make('origin_id')
                        ->label('Origem')
                        ->options(BusinessOrigins::pluck('name', 'id'))
                        ->createOptionForm(function () {
                            return BusinessOrigins::getForm();
                        })
                        ->createOptionUsing(function (array $data): int {
                            return BusinessOrigins::create($data)->id;
                        })
                        ->required()
                        ->native(false),
                    Select::make('tag_id')
                        ->label('Tag')
                        ->options(BusinessTags::pluck('name', 'id'))
                        ->createOptionForm(function () {
                            return BusinessTags::getForm();
                        })
                        ->createOptionUsing(function (array $data): int {
                            return BusinessTags::create($data)->id;
                        })
                        ->required()
                        ->native(false),
                ])->columns(2),
            Section::make('Negócio')
                ->schema([
                    TextInput::make('name')
                        ->label('Nome do negócio')
                        ->required()
                        ->columnSpan(2),
                    Money::make('valuation')
                        ->label('Valor')
                        ->required(),
                    Select::make('status')
                        ->label('Status')
                        ->options(StatusEnum::class)
                        ->default(StatusEnum::RUNNING)
                        ->required()
                        ->native(false),
                    Select::make('responsible_id')
                        ->label('Responsável')
                        ->options(User::pluck('name', 'id'))
                        ->default(auth()->id())
                        ->required()
                        ->native(false),
                    DatePicker::make('closing_forecast')
                        ->label('Previsão de fechamento'),
//                    Select::make('funnel_id')
//                        ->label('Funil')
//                        ->options(BusinessFunnels::pluck('name', 'id'))
//                        ->reactive()
//                        ->afterStateUpdated(function ($state, $set, $get) {
//
//                            $funnel = BusinessFunnels::find($state);
//                            $stages = $funnel->stages;
////                            $set('stage_id', $stages->pluck('name', 'id')->toArray());
//                            $set('stage_id', $stages->pluck('name', 'id')->toArray());
//                        })
//                        ->required()
//                        ->native(false),
                    Select::make('stage_id')
                        ->label('Estágio')
                        ->reactive()
                        ->required()
                        ->native(false)
                        ->default(1),

                ])->columns(2),
        ];
    }
}
