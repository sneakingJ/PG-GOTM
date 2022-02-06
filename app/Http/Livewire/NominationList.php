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
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public \Illuminate\Database\Eloquent\Collection $shortNominations;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public \Illuminate\Database\Eloquent\Collection $longNominations;

    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->shortNominations = Nomination::where('short', 1)->orderBy('created_at', 'desc')->get();
        $this->longNominations = Nomination::where('short', 0)->orderBy('created_at', 'desc')->get();

        return view('livewire.nomination-list');
    }
}
