<?php

namespace App\Http\Livewire;

use App\Lib\MonthStatus;
use App\Models\Month;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use App\Models\Nomination;
use App\Models\Vote;
use Illuminate\Support\Facades\Cookie;

/**
 *
 */
class VotingList extends Component
{
    /**
     * @var string[]
     */
    protected $listeners = ['saveVote', 'activateModal', 'disableModal'];

    /**
     * @var Collection
     */
    public Collection $shortNominations;

    /**
     * @var Collection
     */
    public Collection $longNominations;

    /**
     * @var bool
     */
    public bool $saveOnDrop = false;

    /**
     * @var bool
     */
    public bool $pitchModalActive = false;

    /**
     * @var Nomination
     */
    public Nomination $modalNomination;

    /**
     * @var array
     */
    public array $currentOrder = array();

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->saveOnDrop = Cookie::get('saveOnDrop') ?? false;
    }

    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $user = session('auth');
        $monthId = Month::where('status', MonthStatus::VOTING)->first()->id;

        $this->shortNominations = $this->fetchNominations($user['id'], $monthId, true);
        $this->longNominations = $this->fetchNominations($user['id'], $monthId, false);

        return view('livewire.voting-list');
    }

    /**
     * @return void
     */
    public function updatingSaveOnDrop($value, $key): void
    {
        Cookie::queue('saveOnDrop', $value, 525960);
    }

    /**
     * @param int $nominationId
     * @return void
     */
    public function activateModal(int $nominationId): void
    {
        $this->modalNomination = Nomination::find($nominationId);

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
     * @param $sortOrder
     * @param $previousSortOrder
     * @param $name
     * @param $from
     * @param $to
     * @return void
     */
    public function sortChange($sortOrder, $previousSortOrder, $name, $from, $to): void
    {
        $short = ($name === 'short') ? 1 : 0;
        $this->currentOrder[$short] = $sortOrder;

        if ($this->saveOnDrop) {
            $this->saveVote($name);
        }
    }

    /**
     * @param $name
     * @return void
     */
    public function saveVote($name): void
    {
        $user = session('auth');
        $monthId = Month::where('status', MonthStatus::VOTING)->first()->id;
        $short = ($name === 'short');

        $vote = Vote::where('month_id', $monthId)->where('discord_id', $user['id'])->where('short', $short)->first();

        if (empty($vote)) {
            $vote = new Vote();
            $vote->month_id = $monthId;
            $vote->discord_id = $user['id'];
            $vote->short = $short;
        }

        $vote->rank_1 = $this->currentOrder[$short][0];
        $vote->rank_2 = $this->currentOrder[$short][1];
        $vote->rank_3 = $this->currentOrder[$short][2];

        $vote->save();
    }

    /**
     * @param $userId
     * @param $monthId
     * @param bool $short
     * @return Collection
     */
    private function fetchNominations($userId, $monthId, bool $short): Collection
    {
        $vote = Vote::where('discord_id', $userId)->where('month_id', $monthId)->where('short', $short)->first();

        if (!empty($this->currentOrder[(int)$short])) {
            return new Collection([Nomination::find($this->currentOrder[(int)$short][0]), Nomination::find($this->currentOrder[(int)$short][1]), Nomination::find($this->currentOrder[(int)$short][2])]);
        }

        if (empty($vote)) {
            $nominations = Nomination::where('month_id', $monthId)->where('jury_selected', true)->where('short', $short)->inRandomOrder()->get();
            $this->currentOrder[(int)$short] = $nominations->pluck('id');
            return $nominations;
        }

        $this->currentOrder[(int)$short] = array($vote->rank_1, $vote->rank_2, $vote->rank_3);
        return new Collection([Nomination::find($vote->rank_1), Nomination::find($vote->rank_2), Nomination::find($vote->rank_3)]);
    }
}
