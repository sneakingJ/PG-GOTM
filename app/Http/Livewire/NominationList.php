<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Nomination;

/**
 *
 */
class NominationList extends Component
{
    /**
     * @var \Illuminate\Database\Eloquent\Collection|static[]
     */
    public array|\Illuminate\Database\Eloquent\Collection $nominations;

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->nominations = Nomination::all();

        return view('livewire.nomination-list');
    }
}
