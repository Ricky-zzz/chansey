<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    protected function initials(): Attribute
    {
        return Attribute::get(function ($value, $attributes) {
            $nameParts = explode(' ', $attributes['name']);
            $initials = '';

            foreach ($nameParts as $part) {
                $initials .= strtoupper(substr($part, 0, 1));
            }

            return $initials;
        });
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

    public function pharmacist()
    {
        return $this->hasOne(Pharmacist::class);
    }

    public function createdPatients()
    {
        return $this->hasMany(Patient::class, 'created_by_user_id');
    }

    public function clerkAdmissions()
    {
        return $this->hasMany(Admission::class, 'admitting_clerk_id');
    }

    public function uploadedFiles()
    {
        return $this->hasMany(PatientFile::class, 'uploaded_by_id');
    }

    public function clinicalLogs()
    {
        return $this->hasMany(ClinicalLog::class);
    }

    public function fulfilledOrders()
    {
        return $this->hasMany(MedicalOrder::class, 'fulfilled_by_user_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->user_type === 'admin';
        }

        if ($panel->getId() === 'maintenance') {
            return in_array($this->user_type, ['general_service', 'admin']);
        }

        if ($panel->getId() === 'pharmacy') {
            return in_array($this->user_type, ['pharmacist', 'admin']);
        }

        return false;
    }
}
