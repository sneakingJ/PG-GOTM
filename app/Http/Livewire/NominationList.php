<?php

namespace App\Http\Livewire;

use App\Lib\MonthStatus;
use App\Models\Month;
use Livewire\Component;
use App\Models\Nomination;

/**
 *
 */
class NominationList extends Component
{
    /**
     * @var string[]
     */
    protected $listeners = ['activateModal', 'disableModal'];

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
     * @var string
     */
    public string $modalName = '';

    /**
     * @var string
     */
    public string $modalPitch = '';

    /**
     * @var string
     */
    public string $modalCover = '';

    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
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
        $nomination = Nomination::find($nominationId);
        $this->modalName = html_entity_decode($nomination->game_name);
        $this->modalCover = $nomination->game_cover;
        $this->modalPitch = $this->markupPitch($nomination->pitches()->get());

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
     * @param \Illuminate\Database\Eloquent\Collection $pitches
     * @return string
     */
    private function markupPitch(\Illuminate\Database\Eloquent\Collection $pitches): string
    {
        //$user = session('auth');

        $markup = '<ul>';
        foreach ($pitches as $pitch) {
            $markup .= '<li>';
            /*if ($user['id'] == $pitch->discord_id) {
                $markup .= '<textarea class="pitch_edit textarea" name="pitch_edit">';
            }*/
            $markup .= html_entity_decode($pitch->pitch);

            /*if ($user['id'] == $pitch->discord_id) {
                $markup .= '</textarea>';
            }*/
            $markup .= '</li>';
        }
        $markup .= '</ul>';

        return $markup;
    }
}
