<?php

namespace App\Filament\Resources\AccountsReceiveResource\RelationManagers;

use App\Models\AccountsReceiveInstallments;
use Carbon\Carbon;
use Filament\Actions\RestoreAction;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class InstallmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'installments';

    protected static ?string $label = 'parcela';

    protected static ?string $title = 'Parcelas';

    public function form(Form $form): Form
    {
        return $form->schema(AccountsReceiveInstallments::getForm());
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Todos')
                ->icon('heroicon-o-rectangle-stack'),
            'Em aberto' => Tab::make()
                ->icon('heroicon-o-document')
                ->query(fn ($query) => $query->where('status', 'open')),
            'Pago' => Tab::make()
                ->icon('heroicon-o-check-circle')
                ->query(fn ($query) => $query->where('status', 'paid')),
            'Cancelado' => Tab::make()
                ->icon('heroicon-o-x-circle')
                ->query(fn ($query) => $query->where('status', 'canceled')),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('parcel')
                    ->label('Parcela')
                    ->formatStateUsing(fn (string $state): string => Str::padLeft($state, 2, '0'))
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Vencimento')
                    ->date(format: 'd/m/Y')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pay_date')
                    ->label('Pagamento')
                    ->date(format: 'd/m/Y')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('document_number')
                    ->label('Documento')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Valor')
                    ->money('BRL')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('value_paid')
                    ->label('Valor pago')
                    ->money('BRL')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->icon(fn (string $state): string => match ($state) {
                        'open' => 'heroicon-o-document',
                        'paid' => 'heroicon-o-check-circle',
                        'canceled' => 'heroicon-o-x-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'info',
                        'paid' => 'success',
                        'canceled' => 'danger',
                    })
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
            ])
            ->groups([
                Tables\Grouping\Group::make('status')
                    ->getKeyFromRecordUsing(fn ($record) => $record->status),
            ])
            ->filters([

            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                ActionGroup::make([
                    Action::make('duplicate')
                        ->label('Duplicar parcela')
                        ->icon('heroicon-o-document-duplicate')
                        ->requiresConfirmation()
                        ->action(
                            function ($record) {
                                AccountsReceiveInstallments::create([
                                    'accounts_receive_id' => $record->accounts_receive_id,
                                    'parcel' => $record->parcel + 1,
                                    'due_date' => Carbon::parse($record->due_date)->addMonth(),
                                    'pay_date' => null,
                                    'value' => $record->value,
                                    'discount' => $record->discount,
                                    'interest' => $record->interest,
                                    'fine' => $record->fine,
                                    'value_paid' => $record->value_paid,
                                    'status' => 'open',
                                    'observation' => $record->observation,
                                ]);

                                Notification::make()
                                    ->title('Parcela duplicada')
                                    ->body('A parcela foi duplicada com sucesso.')
                                    ->success()
                                    ->seconds(3)
                                    ->send();
                            }
                        ),
                    Action::make('pay')
                        ->visible(fn ($record) => $record->status === 'open')
                        ->icon('heroicon-o-check-circle')
                        ->label('Marcar como pago')
                        ->requiresConfirmation()
                        ->action(
                            function ($record) {

                                $record->update([
                                    'status' => 'paid',
                                    'pay_date' => now(),
                                    'value_paid' => $record->value,
                                ]);

                                Notification::make()
                                    ->title('Parcela paga')
                                    ->body('A parcela foi marcada como paga com sucesso.')
                                    ->success()
                                    ->seconds(3)
                                    ->send();
                            }
                        ),
                    Action::make('pay_and_create_another')
                        ->visible(fn ($record) => $record->status === 'open')
                        ->icon('heroicon-o-check-circle')
                        ->label('Marcar como pago e criar outra')
                        ->requiresConfirmation()
                        ->action(
                            function ($record) {

                                $record->update([
                                    'status' => 'paid',
                                    'pay_date' => now(),
                                    'value_paid' => $record->value,
                                ]);

                                AccountsReceiveInstallments::create([
                                    'accounts_receive_id' => $record->accounts_receive_id,
                                    'parcel' => $record->parcel + 1,
                                    'due_date' => Carbon::parse($record->due_date)->addMonth(),
                                    'pay_date' => null,
                                    'value' => $record->value,
                                    'discount' => $record->discount,
                                    'interest' => $record->interest,
                                    'fine' => $record->fine,
                                    'value_paid' => $record->value_paid,
                                    'status' => 'open',
                                    'observation' => $record->observation,
                                ]);

                                Notification::make()
                                    ->title('Parcela paga')
                                    ->body('A parcela foi marcada como paga com sucesso.')
                                    ->success()
                                    ->seconds(3)
                                    ->send();
                            }
                        ),

                    DeleteAction::make(),
                    RestoreAction::make(),
                ])->tooltip('Ações'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
                ExportBulkAction::make(),
            ])
            ->emptyStateIcon('heroicon-o-credit-card')
            ->emptyStateHeading('Nenhuma parcela encontrada')
            ->emptyStateDescription('Crie uma nova parcela clicando no botão abaixo')
            ->paginated(false)
            ->striped();

    }
}
