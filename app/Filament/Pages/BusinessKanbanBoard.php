<?php

namespace App\Filament\Pages;

use App\Enums\BusinessEnum;
use App\Models\Business;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Collection;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;

class BusinessKanbanBoard extends KanbanBoard
{
    protected static string $model = Business::class;

    protected static  ?string $label = 'Negócios';
    protected static  ?string $title = 'Negócios';

    protected static string $statusEnum = BusinessEnum::class;


    protected static string $recordTitleAttribute = 'name';

    protected function records(): Collection
    {
        return Business::query()->get();
    }

    protected static string $recordStatusAttribute = 'status';


    protected string $editModalWidth = '2xl';

    protected string $editModalSaveButtonLabel = 'Salvar';

    protected string $editModalCancelButtonLabel = 'Cancelar';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected function getEditModalFormSchema(null|int $recordId): array
    {
        return [
            TextInput::make('name')->label('Nome'),
            TextInput::make('valuation')->label('Avaliação'),
            DatePicker::make('closing_forecast')->label('Previsão de fechamento'),
            DatePicker::make('closing_date')->label('Data de fechamento'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->model(static::$model)
                ->label('Novo negócio')
                ->modalWidth('2xl')
                ->form([
                    TextInput::make('customer_id')->label('Cliente'),
                    TextInput::make('responsible_id')->label('Responsável'),
                    TextInput::make('name')->label('Nome'),
                    TextInput::make('valuation')->label('Avaliação'),
                    DatePicker::make('closing_forecast')->label('Previsão de fechamento'),
                    DatePicker::make('closing_date')->label('Data de fechamento'),
                ]),
        ];
    }
}
