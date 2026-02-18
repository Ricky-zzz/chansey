<?php

namespace App\Enums;

enum AcuityLevel: string
{
    case Severe = 'Severe';
    case High = 'High';
    case Moderate = 'Moderate';
    case Low = 'Low';

    public function score(): int
    {
        return match ($this) {
            self::Severe => 4,
            self::High => 3,
            self::Moderate => 2,
            self::Low => 1,
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Severe => 'error',
            self::High => 'warning',
            self::Moderate => 'info',
            self::Low => 'success',
        };
    }
}
