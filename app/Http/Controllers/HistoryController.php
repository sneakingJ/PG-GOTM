<?php

namespace App\Http\Controllers;

use App\Models\Month;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

/**
 *
 */
class HistoryController extends Component
{
    /**
     * @var int
     */
    public int $monthId = 0;

    protected $queryString = ['monthId'];

    /**
     * @var Collection
     */
    public Collection $months;

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        if (empty($this->monthId)) {
            $this->months = Month::where('status', 'over')->orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        }

        return view('history');
    }
}
