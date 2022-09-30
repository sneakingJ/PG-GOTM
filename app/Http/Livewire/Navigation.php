<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Month;
use App\Lib\MonthStatus;

/**
 *
 */
class Navigation extends Component
{
    /**
     * @var bool
     */
    public bool $nominationExists = false;

    /**
     * @var bool
     */
    public bool $juryExists = false;

    /**
     * @var bool
     */
    public bool $votingExists = false;

    /**
     * @var bool
     */
    public bool $admin = false;

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->nominationExists = Month::where('status', MonthStatus::NOMINATING)->exists();
        $this->juryExists = Month::where('status', MonthStatus::JURY)->exists();
        $this->votingExists = Month::where('status', MonthStatus::VOTING)->exists();

        $user = session('auth');

        if (!empty($user) && in_array($user['id'], explode(',', env('ADMIN_DISCORD_IDS', '')))) {
            $this->admin = true;
        }

        return view('livewire.navigation');
    }
}
