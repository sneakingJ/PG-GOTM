<?php

namespace App\Http\Controllers;

use App\Models\Nomination;
use Livewire\Component;

/**
 *
 */
class VotingController extends Component
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $games = Nomination::where('jury_selected', true)->get();

        return view('voting', ['games' => $games]);
    }
}
