<?php

namespace App\Http\Livewire;

use Livewire\Component;

/**
 *
 */
class NominateMessage extends Component
{
    /**
     * @var string[]
     */
    protected $listeners = ['showSubmitted', 'showError', 'removeAll'];

    /**
     * @var bool
     */
    public bool $submitted = false;

    /**
     * @var string
     */
    public string $errorMessage = '';

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('components.nominate-message');
    }

    /**
     * @return void
     */
    public function showSubmitted()
    {
        $this->submitted = true;
    }

    /**
     * @param string $errorMessage
     * @return void
     */
    public function showError(string $errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return void
     */
    public function removeAll()
    {
        $this->errorMessage = '';
        $this->submitted = false;
    }
}
