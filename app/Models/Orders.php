<?php

namespace App\Models;

use App\Enums\Orders\StatusEnum;
use App\Enums\Orders\TypeEnum;
use App\Filament\Forms\Components\PtbrMoney;
use App\Observers\OrdersObserver;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Leandrocfe\FilamentPtbrFormFields\Money;

#[ObservedBy(OrdersObserver::class)]
class Orders extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'total' => 'decimal:2',
        'type' => TypeEnum::class,
        'status' => StatusEnum::class,
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(OrderProducts::class, 'order_id');
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
                            ->options([
                                'service' => 'Serviço',
                                'sale' => 'Venda',
                            ])
                            ->icons([
                                'service' => 'heroicon-o-cog',
                                'sale' => 'heroicon-o-shopping-cart',
                            ])
                            ->colors([
                                'service' => 'primary',
                                'sale' => 'success',
                            ])
                            ->required()
                            ->default('service'),
                        ToggleButtons::make('status')
                            ->inline()
                            ->default('budget')
                            ->options([
                                'budget' => 'Orçamento',
                                'open' => 'Aberto',
                                'progress' => 'Em andamento',
                                'finished' => 'Finalizado',
                                'canceled' => 'Cancelado',
                                'waiting' => 'Aguardando',
                                'approved' => 'Aprovado',
                            ])
                            ->icons([
                                'budget' => 'heroicon-o-document',
                                'open' => 'heroicon-o-document-duplicate',
                                'progress' => 'heroicon-o-cog',
                                'finished' => 'heroicon-o-check-circle',
                                'canceled' => 'heroicon-o-x-circle',
                                'waiting' => 'heroicon-o-clock',
                                'approved' => 'heroicon-o-check',
                            ])
                            ->required(),
                        Select::make('person_id')
                            ->label('Cliente')
                            ->options(Person::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->createOptionForm(function () {
                                return Person::getForm();
                            })
                            ->createOptionUsing(function (array $data): int {
                                return Person::create($data)->id;
                            })
                            ->loadingMessage('Carregando clientes...')
                            ->native(false),
                        Select::make('user_id')
                            ->label('Responsável')
                            ->options(User::all()->pluck('name', 'id'))
                            ->default(auth()->id())
                            ->searchable()
                            ->required()
                            ->native(false),
                        DatePicker::make('initial_date')
                            ->label('Data de início')
                            ->required()
                            ->default(now()),
                        DatePicker::make('final_date')
                            ->label('Data de término')
                            ->required()
                            ->default(now()->addDays(5))
                            ->rules('after_or_equal:initial_date'),
                    ])
                    ->columns(2),
                Wizard\Step::make('Produtos')
                    ->icon('heroicon-o-shopping-cart')
                    ->schema(OrderProducts::getForm()),
                Wizard\Step::make('Arquivos')
                    ->schema(Files::getForm()),
                Wizard\Step::make('Observações')
                    ->icon('heroicon-o-user')
                    ->schema([
                        MarkdownEditor::make('description')
                            ->label('Descrição'),
                        MarkdownEditor::make('observation')
                            ->label('Observação'),
                        MarkdownEditor::make('note')
                            ->label('Nota'),
                    ])
                    ->columns(2),
            ])
                ->skippable()
                ->columnSpan(2),

        ];
    }
}
