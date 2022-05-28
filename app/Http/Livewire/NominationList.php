<?php

namespace App\Http\Livewire;

use App\Lib\MonthStatus;
use App\Models\Month;
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
        $user = session('auth');
        $this->userId = empty($user) ? 0 : $user['id'];

        $monthId = Month::where('status', MonthStatus::NOMINATING)->first()->id;

        $this->shortNominations = Nomination::where('month_id', $monthId)->where('short', 1)->orderBy('created_at', 'desc')->get();
        $this->longNominations = Nomination::where('month_id', $monthId)->where('short', 0)->orderBy('created_at', 'desc')->get();

        return view('livewire.nomination-list');
    }
}
