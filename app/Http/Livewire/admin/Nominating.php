<?php

namespace App\Http\Livewire\Admin;

use App\Lib\MonthStatus;
use App\Models\Month;
use Livewire\Component;

/**
 *
 */
class Nominating extends Component
{
    /**
     * @var string[]
     */
    protected $listeners = ['nominatingToJury'];

    /**
     * @var Month
     */
    public Month $latestMonth;

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('components.admin.nominating');
    }

    /**
     * @return void
     */
    public function nominatingToJury(): void
    {
        $this->latestMonth->status = MonthStatus::JURY;
        $this->latestMonth->save();
    }
}
