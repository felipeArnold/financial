<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountsReceiveResource\Pages;
use App\Filament\Resources\AccountsReceiveResource\RelationManagers;
use App\Models\AccountsReceive;
use App\Models\AccountsReceiveInstallments;
use App\Models\Person;
use App\Models\User;
use Filament\Actions\RestoreAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Leandrocfe\FilamentPtbrFormFields\Money;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class AccountsReceiveResource extends Resource
{
    protected static ?string $model = AccountsReceive::class;

    protected static ?string $navigationGroup = 'Financeiro';

    protected static ?string $label = 'Contas a receber';

    protected static ?string $navigationLabel = 'Contas a receber';

    protected static ?string $pluralLabel = 'Contas a receber';

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema(AccountsReceive::getForm());
    }

    public static function table(Table $table): Table
    {
        $query = AccountsReceiveInstallments::query()
            ->select(
                'accounts_receives.*',
                'people.name as person_name',
                'users.name as user_name',
                'accounts_receives.id as idAccount',
                'accounts_receive_installments.id as id',
                'accounts_receive_installments.status as status',
                'accounts_receive_installments.due_date as due_date',
                'accounts_receive_installments.pay_date as pay_date',
                'accounts_receive_installments.parcel as parcel',
                'accounts_receive_installments.value as value',
            )
            ->join('accounts_receives', 'accounts_receives.id', '=', 'accounts_receive_installments.accounts_receive_id')
            ->join('people', 'people.id', '=', 'accounts_receives.person_id')
            ->join('users', 'users.id', '=', 'accounts_receives.user_id');

        $table->query($query);

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('person_name')
                    ->label('Cliente')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_name')
                    ->label('Responsável')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('parcel')
                    ->label('Parcela')
                    ->formatStateUsing(fn (string $state): string => Str::padLeft($state, 2, '0'))
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Valor')
                    ->money('BRL')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Vencimento')
                    ->dateTime(format: 'd/m/Y')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pay_date')
                    ->label('Pagamento')
                    ->dateTime(format: 'd/m/Y')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->icon(fn (string $state): string => match ($state) {
                        'open' => 'heroicon-o-document',
                        'canceled' => 'heroicon-o-x-circle',
                        'paid' => 'heroicon-o-check-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'green',
                        'canceled' => 'red',
                        'paid' => 'success',
                    })
                    ->tooltip(fn (string $state): string => match ($state) {
                        'open' => 'Em aberto',
                        'canceled' => 'Vencido',
                        'paid' => 'Pago',
                    })
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
                                    return $query->where('due_date', '>=', $due_date_start);
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
                                    return $query->where('due_date', '<=', $due_date_end);
                                }
                            );
                    }),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Responsável')
                    ->options(fn () => User::all()->pluck('name', 'id')->toArray())
                    ->multiple()
                    ->searchable(),

                Tables\Filters\SelectFilter::make('person_id')
                    ->label('Cliente')
                    ->options(fn () => Person::all()->pluck('name', 'id')->toArray())
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
                    DeleteAction::make()
                        ->hidden(fn (Model $record): bool => $record->status === 'paid'),
                    RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('receive')
                    ->label('Receber contas')
                    ->icon('heroicon-o-currency-dollar')
                    ->requiresConfirmation()
                    ->color('success')
                    ->deselectRecordsAfterCompletion()
                    ->form([
                        Forms\Components\DatePicker::make('payment_date')
                            ->label('Data do pagamento')
                            ->default(now())
                            ->rules([
                                'required',
                            ])
                            ->validationMessages([
                                'required' => 'A data do pagamento é obrigatória',
                            ]),
                    ])
                    ->action(function (Collection $records, array $data) {
                        AccountsReceiveInstallments::query()
                            ->whereIn('id', $records->pluck('id'))
                            ->where('status', '<>', 'paid')
                            ->update([
                                'status' => 'paid',
                                'pay_date' => $data['payment_date'],
                            ]);

                        return Notification::make()
                            ->title('Pagamento efetuado')
                            ->body('As contas selecionadas foram marcadas como pagas com sucesso')
                            ->success()
                            ->seconds(3)
                            ->send();
                    }),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
                ExportBulkAction::make(),
            ])
            ->emptyStateIcon('heroicon-o-credit-card')
            ->emptyStateHeading('Nenhuma conta a receber encontrada')
            ->emptyStateDescription('Crie uma nova conta a receber clicando no botão abaixo')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Criar conta a receber')
                    ->icon('heroicon-m-plus')
                    ->url('accounts-receives/create'),
            ])
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filter'),
            );
    }

//    public static function getRelations(): array
//    {
//        return [
//            RelationManagers\InstallmentsRelationManager::class,
//        ];
//    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccountsReceives::route('/'),
            'create' => Pages\CreateAccountsReceive::route('/create'),
            'edit' => Pages\EditAccountsReceive::route('/{record}/edit'),
        ];
    }
}
