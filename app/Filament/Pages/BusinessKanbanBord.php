<?php

namespace App\Filament\Pages;

use App\Enums\BusinessEnum;
use App\Models\Business;
use App\Models\BusinessStages;
use App\Models\Lead;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
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
            'created' => $record->created_at
        ]);
    }

    public function onStatusChanged(int $recordId, string $status, array $fromOrderedIds, array $toOrderedIds): void
    {
        $record = Business::findOrFail($recordId);

        $record->update([
            'stage_id' => $status
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Criar negociação')
                ->modalHeading('Criar negociação')
                ->form(Business::getForm())
                ->model(Business::class)
        ];
    }
}
