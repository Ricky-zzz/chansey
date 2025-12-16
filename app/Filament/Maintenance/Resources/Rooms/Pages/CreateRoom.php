<?php

namespace App\Filament\Maintenance\Resources\Rooms\Pages;

use App\Filament\Maintenance\Resources\Rooms\RoomResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Bed;

class CreateRoom extends CreateRecord
{
    protected static string $resource = RoomResource::class;

    protected function afterCreate(): void
    {
        $room = $this->record;

        $stationCode = $room->station->station_code;

        for ($i = 1; $i <= $room->capacity; $i++) {
            $letter = chr(64 + $i); 

            Bed::create([
                'room_id' => $room->id,
                'bed_code' => "{$stationCode}-{$room->room_number}-{$letter}",
                'status' => 'Available',
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
