<?php

namespace App\Filament\Resources;

use App\Enums\Orders\StatusEnum;
use App\Filament\Resources\OrdersResource\Pages;
use App\Models\AccountsReceive;
use App\Models\Orders;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class OrdersResource extends Resource
{
    protected static ?string $model = Orders::class;

    protected static ?string $label = 'Ordens';

    protected static ?string $recordTitleAttribute = 'order_number';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form->schema(Orders::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Nº da ordem')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('person.name')
                    ->label('Cliente')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Responsável')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->toggleable()
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('initial_date')
                    ->label('Data inicial')
                    ->toggleable()
                    ->date(format: 'd/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('final_date')
                    ->label('Data final')
                    ->toggleable()
                    ->date(format: 'd/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->toggleable()
                    ->dateTime(format: 'd/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'service' => 'Serviço',
                        'sale' => 'Venda',
                    ])
                    ->label('Tipo')
                    ->placeholder('Todos')
                    ->searchable()
                    ->native(),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                        ->disabled(fn ($record) => $record->status === StatusEnum::approved),
                    DeleteAction::make()
                        ->disabled(fn ($record) => $record->status === StatusEnum::approved),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('generate_account_receivable')
                    ->label('Gerar contas a receber')
                    ->icon('heroicon-o-currency-dollar')
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion()
                    ->color('success')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $accountReceivable = AccountsReceive::create([
                                'tenant_id' => $record->tenant_id,
                                'person_id' => $record->person_id,
                                'user_id' => $record->user_id,
                                'description' => $record->description,
                                'title' => $record->order_number,
                                'amount' => $record->total,
                                'status' => 'open',
                            ]);

                            $accountReceivable->installments()->create([
                                'document_number' => $record->order_number,
                                'value' => $record->total,
                                'due_date' => $record->initial_date,
                                'status' => 'open',
                            ]);

                            $record->update(['status' => 'approved']);
                        }

                        return Notification::make()
                            ->title('Contas a receber geradas')
                            ->body('As contas a receber foram geradas com sucesso')
                            ->success()
                            ->seconds(3)
                            ->send();
                    }),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                ExportBulkAction::make(),
            ])
            ->groups([
                Tables\Grouping\Group::make('created_at')
                    ->label('Criado em')
                    ->date('d/m/Y')
                    ->collapsible(),
            ])
            ->emptyStateIcon('heroicon-o-users')
            ->emptyStateHeading('Nenhum pedido encontrado')
            ->emptyStateDescription('Crie um novo pedido clicando no botão abaixo')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Criar pedido')
                    ->icon('heroicon-m-plus')
                    ->url('orders/create'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $typeOrder = $infolist->record->type == 'service' ? 'serviço' : 'venda';

        return $infolist->schema([
            \Filament\Infolists\Components\Section::make('Ordem de '.$typeOrder)
                ->description('Informações sobre a ordem de '.$typeOrder)
                ->collapsible()
                ->schema([
                    \Filament\Infolists\Components\Group::make([
                        TextEntry::make('order_number')
                            ->label('Nº da ordem'),
                        TextEntry::make('total')
                            ->label('Total')
                            ->money('BRL'),
                        TextEntry::make('type')
                            ->label('Tipo')
                            ->badge(),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge(),
                        TextEntry::make('person.name')
                            ->label('Cliente'),
                        TextEntry::make('user.name')
                            ->label('Responsável'),
                        TextEntry::make('initial_date')
                            ->label('Data de início')
                            ->date(format: 'd/m/Y'),
                        TextEntry::make('final_date')
                            ->label('Data final')
                            ->date(format: 'd/m/Y'),
                    ])->columns(2),

                ]),
            \Filament\Infolists\Components\Section::make('Descrição')
                ->description('Descrição da ordem de '.$typeOrder)
                ->collapsible()
                ->schema([
                    TextEntry::make('description')
                        ->hiddenLabel()
                        ->html(),
                ]),
            \Filament\Infolists\Components\Section::make('Observação')
                ->description('Observações sobre a ordem de '.$typeOrder)
                ->collapsible()
                ->schema([
                    TextEntry::make('observation')
                        ->hiddenLabel()
                        ->html(),
                ]),
            \Filament\Infolists\Components\Section::make('Nota')
                ->description('Notas sobre a ordem de '.$typeOrder)
                ->collapsible()
                ->schema([
                    TextEntry::make('note')
                        ->hiddenLabel()
                        ->html(),
                ]),
            \Filament\Infolists\Components\Section::make('Histórico')
                ->description('Histórico da ordem de '.$typeOrder)
                ->collapsible()
                ->schema([
                    \Filament\Infolists\Components\Group::make([
                        TextEntry::make('created_at')
                            ->label('Criado em')
                            ->dateTime(format: 'd/m/Y H:i'),
                        TextEntry::make('updated_at')
                            ->label('Atualizado em')
                            ->dateTime(format: 'd/m/Y H:i'),
                    ])->columns(2),
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrders::route('/create'),
            'edit' => Pages\EditOrders::route('/{record}/edit'),
            'view' => Pages\ViewOrders::route('/{record}'),
        ];
    }
}
