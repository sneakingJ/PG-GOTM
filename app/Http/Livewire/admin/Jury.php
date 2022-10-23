<?php

namespace App\Http\Livewire\Admin;

use App\Lib\MonthStatus;
use App\Models\Month;
use App\Models\Nomination;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

/**
 *
 */
class Jury extends Component
{
    /**
     * @var string[]
     */
    protected $listeners = ['juryToVoting'];

    /**
     * @var Month
     */
    public Month $latestMonth;

    /**
     * @var Collection
     */
    public Collection $shortGames;

    /**
     * @var Collection
     */
    public Collection $longGames;

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->shortGames = Nomination::where('month_id', $this->latestMonth->id)->where('short', 1)->get();
        $this->longGames = Nomination::where('month_id', $this->latestMonth->id)->where('short', 0)->get();

        return view('components.admin.jury');
    }

    /**
     * @return void
     */
    public function juryToVoting(): void
    {
        $this->latestMonth->status = MonthStatus::JURY;
        $this->latestMonth->save();
    }
}
