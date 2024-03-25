<?php

namespace App\Filament\Resources\PersonResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Leandrocfe\FilamentPtbrFormFields\Cep;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    protected static ?string $modelLabel = 'endereço';

    protected static ?string $title = 'Endereços';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Cep::make('postal_code')
                    ->label('CEP')
                    ->viaCep(
                        mode: 'suffix',
                        errorMessage: 'CEP inválido.',
                        setFields: [
                            'street' => 'logradouro',
                            'number' => 'numero',
                            'complement' => 'complemento',
                            'district' => 'bairro',
                            'city' => 'localidade',
                            'state' => 'uf'
                        ]
                    )
                    ->required(),
                Forms\Components\TextInput::make('street')
                    ->label('Rua')
                    ->required(),
                Forms\Components\TextInput::make('number')
                    ->label('Número')
                    ->required(),
                Forms\Components\TextInput::make('complement')
                    ->label('Complemento'),
                Forms\Components\TextInput::make('district')
                    ->label('Bairro')
                    ->required(),
                Forms\Components\TextInput::make('city')
                    ->label('Cidade')
                    ->required(),
                Forms\Components\TextInput::make('state')
                    ->label('Estado')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street')
            ->columns([
                Tables\Columns\TextColumn::make('street')
                    ->label('Endereço'),
                Tables\Columns\TextColumn::make('number')
                    ->label('Número'),
                Tables\Columns\TextColumn::make('complement')
                    ->label('Complemento'),
                Tables\Columns\TextColumn::make('district')
                    ->label('Bairro'),
                Tables\Columns\TextColumn::make('city')
                    ->label('Cidade'),
                Tables\Columns\TextColumn::make('state')
                    ->label('Estado'),
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
            ->emptyStateHeading('Nenhum endereço encontrado')
            ->emptyStateDescription('Adicione um endereço para esta pessoa')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Adicionar endereço')
                    ->icon('heroicon-m-plus')
            ]);
    }
}
