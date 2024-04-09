<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Business\Resources\LeadResource\Pages;
use App\Filament\Clusters\Business\Resources\LeadResource\RelationManagers;
use App\Models\AccountsReceive;
use App\Models\Lead;
use App\Models\Person;
use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Negócios';

    public static function form(Form $form): Form
    {
        return $form->schema(Lead::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('document')
                    ->label('CPF/CNPJ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birthday')
                    ->label('Data de nascimento')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Deletado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('generate_client')
                        ->label('Gerar cliente')
                        ->icon('heroicon-o-users')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $client = Person::create([
                                    'tenant_id' => auth()->user()->tenants()->first()->id,
                                    'name' => $record->name,
                                    'document' => $record->document,
                                    'birth_date' => $record->birthday,
                                ]);

                                if ($record->email) {
                                    $client->emails()->create([
                                        'address' => $record->email,
                                    ]);
                                }

                                if ($record->phone) {
                                    $client->phones()->create([
                                        'number' => $record->phone,
                                    ]);
                                }
                            }

                            return Notification::make()
                                ->title('Contas a receber geradas')
                                ->body('As contas a receber foram geradas com sucesso')
                                ->success()
                                ->seconds(3)
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                ExportBulkAction::make(),
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
            'index' => Pages\ListLeads::route('/'),
        ];
    }
}
