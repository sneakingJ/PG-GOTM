<?php

namespace App\Http\Livewire;

use App\Lib\MonthStatus;
use App\Models\Month;
use App\Models\Pitch;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\Nomination;
use Livewire\Redirector;

/**
 *
 */
class NominationList extends Component
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
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public \Illuminate\Database\Eloquent\Collection $shortNominations;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public \Illuminate\Database\Eloquent\Collection $longNominations;

    /**
     * @var bool
     */
    public bool $pitchModalActive = false;

    /**
     * @var bool
     */
    public bool $newPitchModalActive = false;

    /**
     * @var string
     */
    public string $userId;

    /**
     * @var Nomination
     */
    public Nomination $nomination;

    /**
     * @var Pitch
     */
    public Pitch $userPitch;

    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = session('auth');
        $this->userId = empty($user) ? 0 : $user['id'];

        $monthId = Month::where('status', MonthStatus::NOMINATING)->first()->id;

        $this->shortNominations = Nomination::where('month_id', $monthId)->where('short', 1)->orderBy('created_at', 'desc')->get();
        $this->longNominations = Nomination::where('month_id', $monthId)->where('short', 0)->orderBy('created_at', 'desc')->get();

        return view('livewire.nomination-list');
    }

    /**
     * @param int $nominationId
     * @return void
     */
    public function activateModal(int $nominationId): void
    {
        $this->nomination = Nomination::find($nominationId);

        $this->userPitch = $this->nomination->pitches()->firstOrNew([
            'discord_id' => $this->userId,
            'nomination_id' => $nominationId
        ]);

        $this->pitchModalActive = true;
    }

    /**
     * @return void
     */
    public function disableModal(): void
    {
        $this->pitchModalActive = false;
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
