<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nurse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'first_name',
        'last_name',
        'address',
        'contact_number',
        'birthdate',
        'license_number',
        'status',
        'date_hired',
        'educational_background',
        'role_level',
        'designation',
        'nurse_type_id',
        'station_id',
        'unit_id',
        'shift_schedule_id',
    ];

    protected $casts = [
        'educational_background' => 'array',
        'date_hired' => 'date',
        'birthdate' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function nurseType()
    {
        return $this->belongsTo(NurseType::class);
    }

    public function shiftSchedule()
    {
        return $this->belongsTo(ShiftSchedule::class);
    }

    public function nursingCarePlans()
    {
        return $this->hasMany(NursingCarePlan::class);
    }

    public function assignedTasks()
    {
        return $this->hasMany(StationTask::class, 'assigned_to_nurse_id');
    }

    /**
     * Get the unit ID - from nurse.unit_id or from station.unit_id (for Head Nurses)
     */
    public function getUnitId()
    {
        return $this->unit_id ?? ($this->station ? $this->station->unit_id : null);
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($nurse) {
            if ($nurse->isDirty('station_id')) {
                $station = Station::find($nurse->station_id);
                $isAdmissionStation = $station && strtolower($station->station_name) === 'admission';
                $nurse->designation = $isAdmissionStation ? 'Admitting' : 'Clinical';
            }
        });
    }
}
