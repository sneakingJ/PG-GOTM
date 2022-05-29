<?php

namespace App\Http\Livewire;

use App\Lib\MonthStatus;
use App\Models\Month;
use App\Models\Vote;
use Livewire\Component;

/**
 *
 */
class VoteStatus extends Component
{
    /**
     * @var string[]
     */
    protected $listeners = ['setVoted'];

    /**
     * @var bool
     */
    public bool $short;

    /**
     * @var string
     */
    public string $userId;

    /**
     * @var bool
     */
    public bool $voted = false;


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = session('auth');
        $this->userId = empty($user) ? 0 : $user['id'];

        $monthId = Month::where('status', MonthStatus::VOTING)->first()->id;

        $this->voted = Vote::where('month_id', $monthId)->where('discord_id', $this->userId)->where('short', $this->short)->exists();

        return view('components.vote-status');
    }

    /**
     * @param bool $status
     * @return void
     */
    public function setVoted(bool $status): void
    {
        $this->voted = $status;
    }
}
