<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Lib\MonthStatus;
use App\Models\Month;

/**
 *
 */
class AdminActions extends Component
{
    /**
     * @var Month
     */
    public Month $latestMonth;

    /**
     * @var MonthStatus
     */
    public MonthStatus $monthStatus;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     */
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $this->latestMonth = Month::orderByDesc('year')->orderByDesc('month')->limit(1)->first();
        $this->monthStatus = MonthStatus::from($this->latestMonth->status);

        return view('livewire.admin-actions');
    }
}
