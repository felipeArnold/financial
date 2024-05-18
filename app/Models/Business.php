<?php

namespace App\Models;

use App\Enums\Business\StatusEnum;
use App\Filament\Forms\Components\PtbrMoney;
use App\Observers\BusinessObserver;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(BusinessObserver::class)]
class Business extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => StatusEnum::class,
        'closing_forecast' => 'datetime',
        'closing_date' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public static function getForm(): array
    {
        return [
            Section::make('Lead')
                ->description('Dados da pessoa relacionada ao negócio')
                ->schema([
                    Select::make('lead_id')
                        ->label('Pessoa')
                        ->options(Lead::pluck('name', 'id'))
                        ->searchable()
                        ->rules('required')
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
                        ->native(false),
                ])->columns(2),
            Section::make('Negócio')
                ->description('Dados gerais da negociação')
                ->schema([
                    TextInput::make('name')
                        ->label('Nome do negócio')
                        ->rules('required'),
                    Select::make('stage_id')
                        ->label('Estágio')
                        ->options(BusinessStages::pluck('name', 'id'))
                        ->default(BusinessStages::first()->id)
                        ->reactive()
                        ->rules('required')
                        ->native(false),
                    PtbrMoney::make('valuation')
                        ->label('Valor')
                        ->rules('required'),
                    ToggleButtons::make('status')
                        ->inline()
                        ->default(StatusEnum::RUNNING)
                        ->options(StatusEnum::class)
                        ->colors([
                            'gain' => 'green',
                            'running' => 'blue',
                            'pending' => 'yellow',
                        ])
                        ->grouped()
                        ->required(),
                    Select::make('responsible_id')
                        ->label('Responsável')
                        ->options(User::pluck('name', 'id'))
                        ->default(auth()->id())
                        ->rules('required')
                        ->native(false),
                    DatePicker::make('closing_forecast')
                        ->label('Previsão de fechamento'),


                ])->columns(2),
        ];
    }


}
