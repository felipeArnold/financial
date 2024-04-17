<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCategory extends Model
{
    protected $guarded = ['id'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public static function getForm(): array
    {
        return [
            TextInput::make('name')
                ->label('Nome')
                ->placeholder('Nome da categoria')
                ->rules([
                    'required',
                    'max:255',
                ])
                ->validationMessages([
                    'name.required' => 'O campo nome é obrigatório',
                    'name.max' => 'O campo nome deve ter no máximo 255 caracteres',
                ]),
        ];
    }
}
