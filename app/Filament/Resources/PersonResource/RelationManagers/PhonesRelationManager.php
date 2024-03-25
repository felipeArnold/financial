<?php

namespace App\Filament\Resources\PersonResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;

class PhonesRelationManager extends RelationManager
{
    protected static string $relationship = 'phones';

    protected static ?string $modelLabel = 'telefone';

    protected static ?string $title = 'Telefones';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                PhoneNumber::make('number')
                    ->label('Telefone')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Telefones')
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('Telefone'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-phone')
            ->emptyStateHeading('Nenhum telefone encontrado')
            ->emptyStateDescription('Adicione um telefone para esta pessoa')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Adicionar telefone')
                    ->icon('heroicon-m-plus')
            ]);
    }
}
