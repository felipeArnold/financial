<?php

namespace App\Models;

use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Observers\UserObserver;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Filament\Forms;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'custumer_id',
        'name',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@example.com');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return Storage::url($this->avatar);
    }


    public static function getForm(): array
    {
        return [
            Section::make('Informações do usuário')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nome')
                        ->required(),
                    Forms\Components\TextInput::make('email')
                        ->label('E-mail')
                        ->email()
                        ->required(),
                    Forms\Components\TextInput::make('password')
                        ->label('Senha')
                        ->password()
                        ->required(),
                    Forms\Components\TextInput::make('password_confirmation')
                        ->label('Confirme a senha')
                        ->password()
                        ->required(),
                ])->columns(),
        ];
    }
}
