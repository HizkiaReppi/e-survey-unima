<?php

namespace App\Helpers;

class PeriodHelper
{
    public static function getCurrentPeriod()
    {
        return session('selected_period_id');
    }

    public static function setCurrentPeriod($periodId)
    {
        session(['selected_period_id' => $periodId]);
    }
}
