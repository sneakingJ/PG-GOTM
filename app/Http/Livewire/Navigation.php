<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Month;
use App\Lib\MonthStatus;

/**
 *
 */
class Navigation extends Component
{
    /**
     * @var bool
     */
    public bool $nominationExists = false;

    /**
     * @var bool
     */
    public bool $votingExists = false;

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->nominationExists = Month::where('status', MonthStatus::NOMINATION)->exists();
        $this->votingExists = Month::where('status', MonthStatus::VOTING)->exists();

        return view('livewire.navigation');
    }
}
