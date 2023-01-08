<?php

namespace App\Http\Livewire;

use Livewire\Component;

/**
 *
 */
class VotingResult extends Component
{
    /**
     * @var int
     */
    public int $monthId;

    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.voting-result');
    }
}
