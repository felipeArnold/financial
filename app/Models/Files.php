<?php

namespace App\Models;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function getForm(): array
    {
        return [
            Repeater::make('files')
                ->label('Arquivos')
                ->relationship()
                ->simple(
                    FileUpload::make('attachment')
                        ->openable()
                        ->downloadable()
                )

        ];
    }
}
