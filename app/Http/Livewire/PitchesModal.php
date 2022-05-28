<?php

namespace App\Http\Livewire;

use App\Models\Nomination;
use App\Models\Pitch;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Redirector;

/**
 *
 */
class PitchesModal extends Component
{
    /**
     * @var string[]
     */
    protected $listeners = ['activateModal', 'disableModal', 'addPitch', 'closeNewPitchModal', 'savePitch'];

    /**
     * @var string[]
     */
    protected $rules = [
        'userPitch.discord_id' => 'required',
        'userPitch.nomination_id' => 'required',
        'userPitch.pitch' => 'required'
    ];

    /**
     * @var bool
     */
    public bool $active = false;

    /**
     * @var bool
     */
    public bool $editPossible = false;

    /**
     * @var Nomination
     */
    public Nomination $nomination;

    /**
     * @var bool
     */
    public bool $newPitchModalActive = false;

    /**
     * @var string
     */
    public string $userId;

    /**
     * @var Pitch
     */
    public Pitch $userPitch;


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        if ($this->editPossible) {
            $user = session('auth');
            $this->userId = empty($user) ? 0 : $user['id'];
        }

        return view('components.pitches-modal');
    }

    /**
     * @param int $nominationId
     * @return void
     */
    public function activateModal(int $nominationId): void
    {
        $this->nomination = Nomination::find($nominationId);

        if ($this->editPossible) {
            $this->userPitch = $this->nomination->pitches()->firstOrNew([
                'discord_id' => $this->userId,
                'nomination_id' => $nominationId
            ]);
        }

        $this->active = true;
    }

    /**
     * @return void
     */
    public function disableModal(): void
    {
        $this->active = false;
    }

    /**
     * @return Redirector|null
     */
    public function addPitch(): ?Redirector
    {
        if (empty($this->userId)) {
            Request()->session()->put('url.intended', '/');

            return redirect()->route('login');
        }

        $this->newPitchModalActive = true;
        return null;
    }

    /**
     */
    public function closeNewPitchModal(): void
    {
        $this->newPitchModalActive = false;
    }

    /**
     */
    public function savePitch(): void
    {
        if (Str::length($this->userPitch->pitch) >= 1) {
            $this->userPitch->pitch = Str::limit($this->userPitch->pitch, 1000, ' (...)');
            $this->userPitch->save();
        } elseif ($this->userPitch->exists) {
            $this->userPitch->delete();
        }

        $this->closeNewPitchModal();
    }
}
