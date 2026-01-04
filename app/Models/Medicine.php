<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'generic_name',
        'brand_name',
        'dosage',
        'form',
        'stock_on_hand',
        'critical_level',
        'price',
        'expiry_date',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'price' => 'decimal:2',
    ];

    public function medicalOrders()
    {
        return $this->hasMany(MedicalOrder::class);
    }

    /**
     * Format medicine display with generic name, brand name, and dosage form
     */
    public function getFormattedLabel()
    {
        $label = $this->generic_name;
        
        if ($this->brand_name) {
            $label .= ' (' . $this->brand_name . ')';
        }
        
        if ($this->dosage) {
            $label .= ' - ' . $this->dosage;
        }
        
        return $label;
    }
}
