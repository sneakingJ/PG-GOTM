<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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
     * @return View|Factory|Application
     */
    public function render(): View|Factory|Application
    {
        return view('livewire.voting-result');
    }
}
