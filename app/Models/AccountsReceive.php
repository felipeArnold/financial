<?php

namespace App\Models;

use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountsReceive extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(AccountsReceiveInstallments::class);
    }

    public static function getForm(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('Dados gerais')
                    ->description('Insira as informações gerais do pedido.')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Select::make('person_id')
                            ->label('Cliente')
                            ->options(Person::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->createOptionForm(function () {
                                return Person::getForm();
                            })
                            ->createOptionUsing(function (array $data): int {
                                return Person::create($data)->id;
                            })
                            ->native(false),
                        Select::make('user_id')
                            ->label('Responsável')
                            ->options(User::all()->pluck('name', 'id'))
                            ->default(auth()->id())
                            ->searchable()
                            ->required()
                            ->native(false),
                        TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->columnSpan(2),
                        MarkdownEditor::make('observation')
                            ->label('Observação')
                            ->columnSpan(2)
                            ->nullable(),
                    ]),
                Wizard\Step::make('Parcelas')
                    ->description('Insira as parcelas do pedido.')
                    ->icon('heroicon-o-currency-dollar')
                    ->schema(AccountsReceiveInstallments::getForm()),
            ])
            ->columnSpan(2)
                ->skippable()
        ];
    }
}
