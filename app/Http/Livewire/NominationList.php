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
    public \Illuminate\Database\Eloquent\Collection $nominations;

    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->nominations = Nomination::orderBy('created_at', 'desc')->get();

        return view('livewire.nomination-list');
    }
}
