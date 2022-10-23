<?php

namespace App\Http\Livewire\Admin;

use App\Lib\MonthStatus;
use App\Models\Month;
use Livewire\Component;

/**
 *
 */
class Voting extends Component
{
    /**
     * @var string[]
     */
    protected $listeners = ['votingToPlaying'];

    /**
     * @var Month
     */
    public Month $latestMonth;

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('components.admin.voting');
    }

    /**
     * @return void
     */
    public function votingToPlaying(): void
    {
        $playingMonths = Month::where('status', MonthStatus::PLAYING)->get();
        foreach ($playingMonths as $playingMonth) {
            $playingMonth->status = MonthStatus::OVER;
            $playingMonth->save();
        }

        $this->latestMonth->status = MonthStatus::PLAYING;
        $this->latestMonth->save();
    }
}
