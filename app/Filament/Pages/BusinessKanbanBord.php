<?php

namespace App\Filament\Pages;

use App\Models\Business;
use App\Models\BusinessOrigins;
use App\Models\BusinessStages;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Leandrocfe\FilamentPtbrFormFields\Money;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;

class BusinessKanbanBord extends KanbanBoard
{
    protected static string $model = Business::class;

    protected static ?string $label = 'Negócios';

    protected static ?string $title = 'Negócios';

    protected static ?string $navigationGroup = 'Negócios';

    protected static string $recordStatusAttribute = 'stage_id';

    public function getTitle(): string
    {
        return 'Negócios';
    }

    public function statuses(): Collection
    {
        return BusinessStages::query()
            ->orderBy('order')
            ->get()
            ->map(function (BusinessStages $stage) {
                return [
                    'id' => $stage->id,
                    'title' => $stage->name,
                ];
            });
    }

    public function additionalRecordData(Model $record): Collection
    {
        return collect([
            'origin' => $record->origin->name,
            'people' => $record->people->name,
            'value' => $record->value,
            'closing_forecast' => $record->closing_forecast,
            'closing_date' => $record->closing_date,
            'created' => $record->created_at,
        ]);
    }

    public function onStatusChanged(int $recordId, string $status, array $fromOrderedIds, array $toOrderedIds): void
    {
        $record = Business::findOrFail($recordId);

        $record->update([
            'stage_id' => $status,
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Criar negociação')
                ->modalHeading('Criar negociação')
                ->form(Business::getForm())
                ->model(Business::class),
        ];
    }

    protected function getEditModalFormSchema(null|int $recordId): array
    {
        return [
            Split::make([
                Section::make([
                    TextInput::make('name')
                        ->label('Nome')
                        ->placeholder('Nome da negociação')
                        ->required(),
                    Money::make('valuation')
                        ->label('Valor')
                        ->placeholder('Valor da negociação')
                        ->required(),
                    DatePicker::make('closing_forecast')
                        ->label('Previsão de fechamento')
                        ->required(),
                    DatePicker::make('closing_date')
                        ->label('Data de fechamento')
                        ->placeholder('Data de fechamento da negociação')
                        ->required(),
                ])
                ->description('Informações da negociação'),
                Section::make([
                    Select::make('origin_id')
                        ->label('Origem')
                        ->placeholder('Selecione a origem da negociação')
                        ->options(BusinessOrigins::query()->pluck('name', 'id')),
                ]),
            ]),
        ];
    }
}
