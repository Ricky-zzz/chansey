<?php

namespace App\Filament\Widgets;

use App\Models\Nurse;
use App\Models\Physician;
use App\Models\GeneralService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Icons\Heroicon;

class StatsOverview extends BaseWidget
{
    protected ?string $pollingInterval = '15s';
    protected function getStats(): array
    {
        return [
            Stat::make('Total Nurses', Nurse::count())
                ->description('Admitting and Clinical Nurses')
                ->descriptionIcon(Heroicon::BuildingOffice)
                ->color('primary')
                ->icon(Heroicon::BuildingOffice),

            Stat::make('Total Physicians', Physician::count())
                ->description('Proffessional Doctors')
                ->descriptionIcon(Heroicon::ChevronDoubleLeft)
                ->color('warning') 
                ->icon(Heroicon::ChevronDoubleLeft),

            Stat::make('Total Gen-Service', GeneralService::count())
                ->description("staff in Maintenance Department")
                ->descriptionIcon(Heroicon::Bars2)
                ->color('success')
                ->icon(Heroicon::Bars2),
        ];
    }
}
