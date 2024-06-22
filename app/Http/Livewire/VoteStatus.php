<?php

namespace App\Http\Livewire;

use App\Lib\MonthStatus;
use App\Models\Month;
use App\Models\Vote;
use App\Models\Ranking;
use Livewire\Component;

/**
 *
 */
class VoteStatus extends Component
{
    /**
     * @var string[]
     */
    protected $listeners = ['updateVoteStatus'];

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
     * @param bool $short
     * @return void
     */
    public function mount($short)
    {
        $this->short = $short;
        $this->updateVoteStatus(['short' => $this->short]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('components.vote-status');
    }

    /**
     * @param bool $status
     * @return void
     */
    public function updateVoteStatus($params)
    {
        if ($params['short'] !== $this->short) {
            return;
        }

        $user = session('auth');
        $this->userId = empty($user) ? '0' : $user['id'];

        $monthId = Month::where('status', MonthStatus::VOTING)->first()->id;

        $vote = Vote::where('month_id', $monthId)
            ->where('discord_id', $this->userId)
            ->where('short', $this->short)
            ->first();

        $this->voted = $vote && Ranking::where('vote_id', $vote->id)->exists();
    }
}
