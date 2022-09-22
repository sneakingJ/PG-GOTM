<?php

namespace App\Http\Controllers;

use Livewire\Component;
use App\Models\JuryMember;

/**
 *
 */
class JuryMemberController extends Component
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = session('auth');

        if (empty($user) || !JuryMember::where('discord_id', $user['id'])->where('active', true)->exists()) {
            return view('jury-members', ['members' => JuryMember::where('active', true)->get()]);
        }

        return view('jury-duty');
    }
}
