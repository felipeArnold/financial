<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersonResource\Pages;
use App\Filament\Resources\PersonResource\RelationManagers;
use App\Models\Person;
use Filament\Actions\RestoreAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Leandrocfe\FilamentPtbrFormFields\Document;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $label = 'Pessoas';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->rules([
                        'required',
                        'max:50'
                    ])
                    ->required(),
                Forms\Components\TextInput::make('surname')
                    ->label('Apelido')
                    ->rules([
                        'nullable',
                        'max:50'
                    ]),
                Document::make('document')
                    ->label('CPF/CNPJ')
                    ->dynamic()
                    ->required(),
                Forms\Components\DatePicker::make('birth_date')
                    ->label('Data de nascimento')
                    ->required(),
                Forms\Components\TextInput::make('nationality')
                    ->label('Nacionalidade')
                    ->rules([
                        'nullable',
                        'max:50'
                    ]),
                Forms\Components\TextInput::make('naturalness')
                    ->label('Naturalidade')
                    ->rules([
                        'nullable',
                        'max:50'
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->description(fn($record) => $record->surname)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('document')
                    ->label('CPF/CNPJ')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('birth_date')
                    ->label('Data de nascimento')
                    ->date()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
                ExportBulkAction::make()
            ])
            ->emptyStateIcon('heroicon-o-users')
            ->emptyStateHeading('Nenhuma pessoa encontrada')
            ->emptyStateDescription('Crie uma nova pessoa clicando no botão abaixo')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Criar pessoa')
                    ->icon('heroicon-m-plus')
                    ->url('people/create')
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeople::route('/'),
            'create' => Pages\CreatePerson::route('/create'),
            'edit' => Pages\EditPerson::route('/{record}/edit'),
        ];
    }
}
