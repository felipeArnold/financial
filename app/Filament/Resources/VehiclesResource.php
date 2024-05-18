<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehiclesResource\Pages;
use App\Filament\Resources\VehiclesResource\RelationManagers;
use App\Models\Vehicles\Vehicles;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Leandrocfe\FilamentPtbrFormFields\Money;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class VehiclesResource extends Resource
{
    protected static ?string $model = Vehicles::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $modelLabel = 'Veículo';

    protected static ?string $pluralLabel = 'Veículos';

    protected static ?string $recordTitleAttribute = 'plate';

    public static function form(Form $form): Form
    {
        return $form->schema(Vehicles::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('plate')
                    ->label('Placa')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Proprietário')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('model.brand.name')
                    ->label('Marca')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('model.name')
                    ->label('Modelo')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('year')
                    ->label('Ano')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mileage')
                    ->label('Quilometragem')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('purchase_price')
                    ->label('Preço de compra')
                    ->money('BRL')
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price_sale')
                    ->label('Preço de venda')
                    ->money('BRL')
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('purchase_date')
                    ->label('Data de compra')
                    ->toggleable()
                    ->date(format: 'd/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sale_date')
                    ->label('Data de venda')
                    ->toggleable()
                    ->date(format: 'd/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->date(format: 'd/m/Y H:i:s')
                    ->toggleable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
                ExportBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
