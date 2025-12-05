<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class BadgeGenerator
{
    public static function generate(string $role, string $firstName, string $lastName): string
    {
        $prefix = match($role) {
            'nurse' => 'NUR',
            'physician' => 'DOC',
            'general_service' => 'SVC',
            'admin' => 'ADM',
            default => 'STF',
        };

        $initials = Str::upper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
        
        $searchPattern = "{$prefix}-{$initials}-%";

        $lastUser = User::where('badge_id', 'LIKE', $searchPattern)
            ->orderBy('id', 'desc')
            ->first();

        if (! $lastUser) {
            $number = 1;
        } else {
            $parts = explode('-', $lastUser->badge_id);
            $number = intval(end($parts)) + 1;
        }

        return "{$prefix}-{$initials}-" . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}