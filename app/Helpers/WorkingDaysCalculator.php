<?php

namespace App\Helpers;

use App\Models\Holiday;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class WorkingDaysCalculator
{
    /**
     * Calculate the number of working days between two dates.
     * Excludes weekends (Saturday & Sunday) and holidays from the holidays table.
     *
     * @param  string|Carbon  $from
     * @param  string|Carbon  $to
     * @return int
     */
    public static function calculate($from, $to): int
    {
        $startDate = Carbon::parse($from)->startOfDay();
        $endDate = Carbon::parse($to)->startOfDay();

        if ($endDate->lt($startDate)) {
            return 0;
        }

        // Fetch all holidays within the date range
        $holidays = Holiday::whereBetween('holiday_date', [
            $startDate->toDateString(),
            $endDate->toDateString(),
        ])->pluck('holiday_date')
          ->map(fn ($date) => Carbon::parse($date)->toDateString())
          ->toArray();

        $workingDays = 0;
        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            // Skip weekends (Saturday = 6, Sunday = 0)
            if ($date->isWeekend()) {
                continue;
            }

            // Skip holidays
            if (in_array($date->toDateString(), $holidays)) {
                continue;
            }

            $workingDays++;
        }

        return max($workingDays, 0);
    }
}
