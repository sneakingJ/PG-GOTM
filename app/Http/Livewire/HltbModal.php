<?php

namespace App\Http\Livewire;

use Livewire\Component;
use IvanKayzer\HowLongToBeat\HowLongToBeat;

/**
 *
 */
class HltbModal extends Component
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
     * @var array
     */
    public array $results = array();


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('components.hltb-modal');
    }

    /**
     * @param string $gameName
     * @return void
     */
    public function activateModal(string $gameName)
    {
        $hl2b = new HowLongToBeat();
        $result = $hl2b->search($gameName);

        $this->results = $result['Results'];
        $this->active = true;
    }

    /**
     * @return void
     */
    public function disableModal()
    {
        $this->active = false;
    }
}
