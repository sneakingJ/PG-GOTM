<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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
     * @var bool
     */
    public bool $voted = false;

    /**
     * @param bool $short
     * @param $voted
     * @return void
     */
    public function mount(bool $short, $voted): void
    {
        $this->short = $short;
        $this->voted = $voted;
    }

    /**
     * @return Application|Factory|View
     */
    public function render(): Application|Factory|View
    {
        return view('components.vote-status');
    }

    /**
     * @param $params
     * @return void
     */
    public function updateVoteStatus($params): void
    {
        if ($params['short'] === $this->short) {
            $this->voted = $params['voted'];
        }
    }
}
