<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PatientFile extends Model
{
    protected $fillable = [
        'patient_id',
        'admission_id',
        'medical_order_id',
        'file_path',
        'file_name',
        'result_type',
        'document_type',
        'description',
        'uploaded_by_id',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    public function medicalOrder(): BelongsTo
    {
        return $this->belongsTo(MedicalOrder::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_id');
    }

    /**
     * Get human-readable file size
     */
    public function getFileSizeReadableAttribute()
    {
        if (!$this->file_path || !Storage::exists($this->file_path)) {
            return 'Unknown';
        }

        $size = Storage::size($this->file_path);
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($size, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}