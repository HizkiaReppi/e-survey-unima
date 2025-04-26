<?php

namespace App\View\Components;

use App\Helpers\PeriodHelper;
use App\Models\Period;
use Illuminate\View\Component;

class Navbar extends Component
{
    public function render()
    {
        $periods = Period::all();

        $currentPeriodId = null;
        if(PeriodHelper::getCurrentPeriod() == null) {
            PeriodHelper::setCurrentPeriod($periods->first()->id);
            $currentPeriodId = $periods->first()->id;
        } else {
            $currentPeriodId = PeriodHelper::getCurrentPeriod();
        }

        return view('components.navbar', compact('periods', 'currentPeriodId'));
    }
}
