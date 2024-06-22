<?php

namespace App\Http\Livewire;

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
        if ($params['short'] === $this->short) {
            $this->voted = $params['voted'];
        }
    }
}
