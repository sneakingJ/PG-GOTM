<?php

namespace App\Http\Controllers;

use Livewire\Component;

/**
 *
 */
class PrivacyController extends Component
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('privacy');
    }
}
