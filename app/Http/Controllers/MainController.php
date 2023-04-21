<?php

namespace App\Http\Controllers;

use App\Lib\MonthStatus;
use App\Models\Month;
use Livewire\Component;

/**
 *
 */
class MainController extends Component
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
     * @var bool
     */
    public bool $juryExists = false;

    /**
     * @var bool
     */
    public bool $playingExists = false;

    /**
     * @var int
     */
    public int $monthIdVoting = 1;

    /**
     * @var int
     */
    public int $monthIdPlaying = 1;


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->nominationExists = Month::where('status', MonthStatus::NOMINATING)->exists();
        $this->votingExists = Month::where('status', MonthStatus::VOTING)->exists();
        $this->juryExists = Month::where('status', MonthStatus::JURY)->exists();
        $this->playingExists = Month::where('status', MonthStatus::PLAYING)->exists();

        if ($this->votingExists) {
            $this->monthIdVoting = Month::where('status', MonthStatus::VOTING)->first()->id;
        }
        if ($this->playingExists) {
            $this->monthIdPlaying = Month::where('status', MonthStatus::PLAYING)->first()->id;
        }

        return view('main');
    }
}
