<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrdersResource\Pages;
use App\Filament\Resources\OrdersResource\RelationManagers;
use App\Models\Orders;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class OrdersResource extends Resource
{
    protected static ?string $model = Orders::class;

    protected static  ?string $label = 'Serviço';

    protected static ?string $navigationGroup = 'Ordens';

    protected static ?string $recordTitleAttribute = 'order_number';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    ->color(fn (string $state): string => match ($state) {
                        'service' => 'primary',
                        'sale' => 'success',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'service' => 'heroicon-o-cog',
                        'sale' => 'heroicon-o-shopping-cart',
                    })
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'budget' => 'primary',
                        'open' => 'info',
                        'progress' => 'warning',
                        'finished' => 'success',
                        'canceled' => 'danger',
                        'waiting' => 'warning',
                        'approved' => 'success',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'budget' => 'heroicon-o-document',
                        'open' => 'heroicon-o-document-duplicate',
                        'progress' => 'heroicon-o-cog',
                        'finished' => 'heroicon-o-check',
                        'canceled' => 'heroicon-o-x-circle',
                        'waiting' => 'heroicon-o-clock',
                        'approved' => 'heroicon-o-check-circle',
                    })
                    ->searchable()
                    ->toggleable()
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
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                ExportBulkAction::make()
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
                    ->url('orders/create')
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrders::route('/create'),
            'edit' => Pages\EditOrders::route('/{record}/edit'),
        ];
    }
}
