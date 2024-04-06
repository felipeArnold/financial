<?php

namespace App\Filament\Clusters\Business\Resources;

use App\Filament\Clusters\Business;
use App\Filament\Resources\BusinessTagsResource\Pages;
use App\Filament\Resources\BusinessTagsResource\RelationManagers;
use App\Models\BusinessTags;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class BusinessTagsResource extends Resource
{
    protected static ?string $model = BusinessTags::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Tags';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $cluster = Business::class;

    public static function form(Form $form): Form
    {
        return $form->schema(BusinessTags::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\ColorColumn::make('color')
                    ->label('Cor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                ExportBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => BusinessTagsResource\Pages\ListBusinessTags::route('/'),
        ];
    }
}
