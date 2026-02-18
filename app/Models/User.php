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
        'profile_image_path',
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

    public function DTR()
    {
        return $this->hasMany(DailyTimeRecord::class);
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

    public function transferRequests()
    {
        return $this->hasMany(TransferRequest::class, 'requested_by_user_id');
    }

    public function accountant()
    {
        return $this->hasOne(Accountant::class);
    }

    public function processedBillings()
    {
        return $this->hasMany(Billing::class, 'processed_by');
    }

    public function memos()
    {
        return $this->hasMany(Memo::class, 'created_by_user_id');
    }

    public function stationTask()
    {
        return $this->hasMany(StationTask::class, 'created_by_user_id');
    }

    public function endorsmentsOutgoing()
    {
        return $this->hasMany(Endorsment::class, 'outgoing_nurse_id');
    }

    public function endorsmentsIncoming()
    {
        return $this->hasMany(Endorsment::class, 'incoming_nurse_id');
    }

    public function endorsmentNotes()
    {
        return $this->hasMany(EndorsmentNote::class);
    }

    public function endorsmentViews()
    {
        return $this->hasMany(EndorsmentViewer::class);
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

        if ($panel->getId() === 'chief') {
            return $this->user_type === 'nurse' && $this->nurse->role_level === 'Chief';
        }

        if ($panel->getId() === 'supervisor') {
        return $this->user_type === 'nurse' && $this->nurse->role_level === 'Supervisor';
    }

        return false;
    }
}
