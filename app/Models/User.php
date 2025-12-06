<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'badge_id',
        'email',
        'password',
        'user_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            // 'email_verified_at' => 'datetime', 
            'password' => 'hashed',
        ];
    }


    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function nurse()
    {
        return $this->hasOne(Nurse::class);
    }

    public function physician()
    {
        return $this->hasOne(Physician::class);
    }

    public function generalService()
    {
        return $this->hasOne(GeneralService::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->user_type === 'admin';
        }

        if ($panel->getId() === 'maintenance') {
            return in_array($this->user_type, ['general_service', 'admin']);
        }

        return false;
    }
}
