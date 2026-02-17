<?php

namespace App\Filament\Chief\Widgets;

use App\Models\Nurse;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Icons\Heroicon;

class NurseStatsWidget extends BaseWidget
{
    protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        return [
            Stat::make('Station Heads', Nurse::where('role_level', 'Head')->count())
                ->description('Head Nurses managing stations')
                ->descriptionIcon(Heroicon::User)
                ->color('primary')
                ->icon(Heroicon::User),

            Stat::make('Unit Supervisors', Nurse::where('role_level', 'Supervisor')->count())
                ->description('Supervisors overseeing units')
                ->descriptionIcon(Heroicon::ChartBar)
                ->color('success')
                ->icon(Heroicon::ChartBar),

            Stat::make('Staff Nurses', Nurse::where('role_level', 'Staff')->count())
                ->description('Clinical and Admitting nurses')
                ->descriptionIcon(Heroicon::Heart)
                ->color('warning')
                ->icon(Heroicon::Heart),
        ];
    }
}
