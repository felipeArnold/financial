<?php

namespace App\Models;

use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasTenants
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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

    public function getTenants(Panel $panel): Collection
    {
        return $this->tenants;
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class);
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->tenants->contains($tenant);
    }

    public function tenant(): BelongsToMany
    {
        return $this->tenants()->where('tenant_id', Filament::getTenant()->getAttribute('id'));
    }

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
                        ->revealable()
                        ->required(fn(string $operation): bool => $operation === 'create')
                        ->dehydrated(fn(?string $state) => filled($state)),
                    Forms\Components\TextInput::make('password_confirmation')
                        ->label('Confirme a senha')
                        ->password()
                        ->revealable()
                        ->required(fn(string $operation): bool => $operation === 'create')
                        ->dehydrated(fn(?string $state) => filled($state)),
                    FileUpload::make('avatar')
                        ->label('Avatar')
                        ->image()
                        ->imageEditor()
                        ->acceptedFileTypes(['image/*'])
                        ->rules(['image', 'max:1024']),
                ])->columns(),
        ];
    }
}
