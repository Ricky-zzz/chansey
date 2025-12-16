<?php

namespace App\Filament\Maintenance\Widgets;

use App\Models\Bed;
use App\Models\Room;
use App\Models\Station;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Icons\Heroicon;

class StatsOverview extends BaseWidget
{
    protected ?string $pollingInterval = '15s';
    protected function getStats(): array
    {
        $availableBeds = Bed::where('status', 'Available')->count();
        $totalBeds = Bed::count();

        return [
            Stat::make('Total Stations', Station::count())
                ->description('Active Wards & Wings')
                ->descriptionIcon(Heroicon::BuildingOffice)
                ->color('primary')
                ->icon(Heroicon::BuildingOffice),

            Stat::make('Total Rooms', Room::count())
                ->description('Private, Wards, ICU, ER')
                ->descriptionIcon(Heroicon::ChevronDoubleLeft)
                ->color('warning') 
                ->icon(Heroicon::ChevronDoubleLeft),

            Stat::make('Total Beds', $totalBeds)
                ->description("{$availableBeds} beds currently available")
                ->descriptionIcon(Heroicon::Bars2)
                ->color($availableBeds > 0 ? 'success' : 'danger')
                ->icon(Heroicon::Bars2),
        ];
    }
}
