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
    protected $listeners = ['saveVote', 'deleteVote'];

    /**
     * @var int
     */
    public int $monthId = 0;

    /**
     * @var string
     */
    public string $userId;

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
    public bool $votedShort = false;

    /**
     * @var bool
     */
    public bool $votedLong = false;

    /**
     * @var bool
     */
    public bool $saveOnDrop = false;

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
        $this->monthId = Month::where('status', MonthStatus::VOTING)->first()->id;

        $user = session('auth');
        $this->userId = empty($user) ? 0 : $user['id'];

        $this->shortNominations = $this->fetchNominations(true);
        $this->longNominations = $this->fetchNominations(false);

        $this->votedShort = Vote::where('month_id', $this->monthId)->where('discord_id', $this->userId)->where('short', true)->exists();
        $this->votedLong = Vote::where('month_id', $this->monthId)->where('discord_id', $this->userId)->where('short', false)->exists();

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
     * @param $sortOrder
     * @param $previousSortOrder
     * @param string $name
     * @param $from
     * @param $to
     * @return void
     */
    public function sortChange($sortOrder, $previousSortOrder, string $name, $from, $to): void
    {
        $short = ($name === 'short') ? 1 : 0;
        $this->currentOrder[$short] = $sortOrder;

        if ($this->saveOnDrop) {
            $this->saveVote($short);
        }
    }

    /**
     * @param bool $short
     * @return void
     */
    public function saveVote(bool $short): void
    {
        $vote = Vote::where('month_id', $this->monthId)->where('discord_id', $this->userId)->where('short', $short)->first();

        if (empty($vote)) {
            $vote = new Vote();
            $vote->month_id = $this->monthId;
            $vote->discord_id = $this->userId;
            $vote->short = $short;
        }

        $vote->rank_1 = $this->currentOrder[$short][0];
        $vote->rank_2 = $this->currentOrder[$short][1];
        $vote->rank_3 = $this->currentOrder[$short][2];

        $vote->save();

        $this->emitTo('vote-status', 'setVoted', true);
    }

    /**
     * @param bool $short
     * @return void
     */
    public function deleteVote(bool $short): void
    {
        $vote = Vote::where('month_id', $this->monthId)->where('discord_id', $this->userId)->where('short', $short)->first();

        if (!empty($vote)) {
            $vote->delete();

            $this->emitTo('vote-status', 'setVoted', false);
        }
    }

    /**
     * @param bool $short
     * @return Collection
     */
    private function fetchNominations(bool $short): Collection
    {
        $vote = Vote::where('discord_id', $this->userId)->where('month_id', $this->monthId)->where('short', $short)->first();

        if (!empty($this->currentOrder[(int)$short])) {
            return new Collection([Nomination::find($this->currentOrder[(int)$short][0]), Nomination::find($this->currentOrder[(int)$short][1]), Nomination::find($this->currentOrder[(int)$short][2])]);
        }

        if (empty($vote)) {
            $nominations = Nomination::where('month_id', $this->monthId)->where('jury_selected', true)->where('short', $short)->inRandomOrder()->get();
            $this->currentOrder[(int)$short] = $nominations->pluck('id');
            return $nominations;
        }

        $this->currentOrder[(int)$short] = array($vote->rank_1, $vote->rank_2, $vote->rank_3);
        return new Collection([Nomination::find($vote->rank_1), Nomination::find($vote->rank_2), Nomination::find($vote->rank_3)]);
    }
}
