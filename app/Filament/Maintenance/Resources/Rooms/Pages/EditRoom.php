<?php

namespace App\Filament\Maintenance\Resources\Rooms\Pages;

use App\Filament\Maintenance\Resources\Rooms\RoomResource;
use App\Models\Bed;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditRoom extends EditRecord
{
    protected static string $resource = RoomResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        $record->update($data);

        $stationCode = $record->station->station_code;

        $addedBeds = 0;

        for ($i = 1; $i <= $record->capacity; $i++) {
            $letter = chr(64 + $i);

            $bedCode = "{$stationCode}-{$record->room_number}-{$letter}";

            $exists = Bed::where('room_id', $record->id)
                ->where('bed_code', $bedCode)
                ->exists();

            if (! $exists) {
                Bed::create([
                    'room_id' => $record->id,
                    'bed_code' => $bedCode,
                    'status' => 'Available',
                ]);
                $addedBeds++;
            }
        }

        if ($addedBeds > 0) {
            Notification::make()
                ->title("Capacity Increased")
                ->body("Successfully added {$addedBeds} new bed(s) to match capacity.")
                ->success()
                ->send();
        } elseif ($record->beds()->count() > $record->capacity) {
            $extra = $record->beds()->count() - $record->capacity;
            Notification::make()
                ->title("Capacity Reduced")
                ->body("Note: {$extra} extra beds remain. Please delete them manually if they are empty.")
                ->warning()
                ->send();
        }

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
