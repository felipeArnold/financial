<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountsReceiveResource\Pages;
use App\Filament\Resources\AccountsReceiveResource\RelationManagers;
use App\Models\AccountsReceive;
use Filament\Actions\RestoreAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class AccountsReceiveResource extends Resource
{
    protected static ?string $model = AccountsReceive::class;

    protected static ?string $label = 'Contas a receber';

    protected static ?string $navigationLabel  = 'Contas a receber';
    protected static ?string $pluralLabel  = 'Contas a receber';

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form->schema(AccountsReceive::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('person.name')
                    ->label('Cliente')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Responsável')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('installments.parcel')
                    ->label('Parcelas')
                    ->formatStateUsing(fn (string $state): string => Str::padLeft($state, 2, '0'))
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime(format: 'd/m/Y H:i')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('due_date_start')
                    ->form([
                        Forms\Components\DatePicker::make('due_date_start')
                            ->label('Data de vencimento de')
                            ->default(now()->startOfMonth())
                            ->native(false)
                            ->required(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['due_date_start'],
                                function (Builder $query, $due_date_start) {
                                    return $query->whereHas('installments', function (Builder $query) use ($due_date_start) {
                                        return $query->where('due_date', '>=', $due_date_start);
                                    });
                                }
                            );
                    }),
                Tables\Filters\Filter::make('due_date_end')
                    ->form([
                        Forms\Components\DatePicker::make('due_date_end')
                            ->label('Data de vencimento até')
                            ->default(now()->endOfMonth())
                            ->native(false)
                            ->required(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['due_date_end'],
                                function (Builder $query, $due_date_end) {
                                    return $query->whereHas('installments', function (Builder $query) use ($due_date_end) {
                                        return $query->where('due_date', '<=', $due_date_end);
                                    });
                                }
                            );
                    }),

                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Responsável')
                    ->options(fn () => \App\Models\User::all()->pluck('name', 'id')->toArray())
                    ->multiple()
                    ->searchable(),

                Tables\Filters\SelectFilter::make('person_id')
                    ->label('Cliente')
                    ->options(fn () => \App\Models\Person::all()->pluck('name', 'id')->toArray())
                    ->multiple()
                    ->searchable(),
            ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->filtersFormWidth(MaxWidth::class::FourExtraLarge)
            ->groups([
                Tables\Grouping\Group::make('user.name')
                    ->label('Responsável')
                    ->collapsible(),
                Tables\Grouping\Group::make('person.name')
                    ->label('Cliente')
                    ->collapsible(),
                Tables\Grouping\Group::make('installments.status')
                    ->label('Status')
                    ->collapsible(),
                Tables\Grouping\Group::make('installments.due_date')
                    ->label('Vencimento')
                    ->collapsible(),
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
            ->emptyStateIcon('heroicon-o-credit-card')
            ->emptyStateHeading('Nenhuma conta a receber encontrada')
            ->emptyStateDescription('Crie uma nova conta a receber clicando no botão abaixo')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Criar conta a receber')
                    ->icon('heroicon-m-plus')
                    ->url('accounts-receives/create')
            ])
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filter'),
            );
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\InstallmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccountsReceives::route('/'),
            'create' => Pages\CreateAccountsReceive::route('/create'),
            'edit' => Pages\EditAccountsReceive::route('/{record}/edit'),
        ];
    }
}
