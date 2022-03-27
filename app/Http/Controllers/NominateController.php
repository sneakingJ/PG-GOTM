<?php

namespace App\Http\Controllers;

use Livewire\Component;
use App\Lib\MonthStatus;
use App\Models\Month;

/**
 *
 */
class NominateController extends Component
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function mount()
    {
        if (!Month::where('status', MonthStatus::NOMINATING)->exists()) {
            return redirect(route('main'));
        }
    }

    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('nominate');
    }
}
