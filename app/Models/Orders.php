<?php

namespace App\Models;

use App\Enums\OrdersStatusEnum;
use App\Observers\OrdersObserver;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Filament\Forms;

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
//        'status' => OrdersStatusEnum::class,
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getForm(): array
    {
        return [
            Section::make('Informações do gerais')
                ->schema([
                    Forms\Components\ToggleButtons::make('type')
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
                        ->columnSpan([3])
                        ->grouped()
                        ->default('service'),
                    Forms\Components\ToggleButtons::make('status')
                        ->inline()
                        ->columnSpan([9])
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
                        ->grouped()
                        ->required(),
                    Forms\Components\Select::make('person_id')
                        ->label('Cliente')
                        ->options(Person::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->native(false),
                    Forms\Components\Select::make('user_id')
                        ->label('Responsável')
                        ->options(User::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->native(false),
                    Forms\Components\DatePicker::make('initial_date')
                        ->label('Data de início')
                        ->required()
                        ->default(now()),
                    Forms\Components\DatePicker::make('final_date')
                        ->label('Data de término')
                        ->required()
                        ->rules('after_or_equal:initial_date'),
                    Forms\Components\MarkdownEditor::make('description')
                        ->label('Descrição'),
                    Forms\Components\MarkdownEditor::make('observation')
                        ->label('Observação'),
                    Forms\Components\MarkdownEditor::make('note')
                        ->label('Nota'),
                ])->columns(),
        ];
    }
}
