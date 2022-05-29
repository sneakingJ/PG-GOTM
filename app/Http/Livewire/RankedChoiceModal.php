<?php

namespace App\Http\Livewire;

use Livewire\Component;

/**
 *
 */
class RankedChoiceModal extends Component
{
    /**
     * @var string[]
     */
    protected $listeners = ['activateModal', 'disableModal'];

    /**
     * @var bool
     */
    public bool $active = false;


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('components.ranked-choice-modal');
    }

    /**
     * @return void
     */
    public function activateModal(): void
    {
        $this->active = true;
    }

    /**
     * @return void
     */
    public function disableModal(): void
    {
        $this->active = false;
    }
}
