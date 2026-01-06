<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferRequest extends Model
{
    protected $guarded = [];

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function medicalOrder()
    {
        return $this->belongsTo(MedicalOrder::class);
    }

    public function requestor()
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function targetStation()
    {
        return $this->belongsTo(Station::class, 'target_station_id');
    }

    public function targetBed()
    {
        return $this->belongsTo(Bed::class, 'target_bed_id');
    }
}