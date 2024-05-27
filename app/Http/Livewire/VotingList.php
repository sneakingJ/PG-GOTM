<?php

namespace App\Http\Livewire;

use App\Lib\MonthStatus;
use App\Models\Month;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use App\Models\Nomination;
use App\Models\Vote;
use App\Models\Ranking;
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
        $vote = Vote::firstOrNew([
            'month_id' => $this->monthId,
            'discord_id' => $this->userId,
            'short' => $short
        ]);

        $vote->save();

        Ranking::where('vote_id', $vote->id)->delete();

        foreach ($this->currentOrder[$short] as $rank => $nominationId) {
            Ranking::create([
                'vote_id' => $vote->id,
                'nomination_id' => $nominationId,
                'rank' => $rank + 1,
            ]);
        }

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
            Ranking::where('vote_id', $vote->id)->delete();
            $vote->delete();

            $this->emitTo('vote-status', 'setVoted', false);
        }
    }

    /**
     * @param bool $short
     * @param int $shortKey
     * @return Collection
     */
    private function getOrderedNominations(array $order, int $shortKey): Collection
    {
        $nominations = Nomination::findMany($order);

        return $nominations->sortBy(function ($nomination) use ($shortKey) {
            return array_search($nomination->id, $this->currentOrder[$shortKey]);
        });
    }

    /**
     * @param int $shortKey
     * @return Collection
     */
    private function getRandomNominations(int $shortKey): Collection
    {
        $nominations = Nomination::where('month_id', $this->monthId)
            ->where('jury_selected', true)
            ->where('short', $shortKey)
            ->inRandomOrder()
            ->get();

        $this->currentOrder[$shortKey] = $nominations->pluck('id');

        return $nominations;
    }


    /**
     * @param bool $short
     * @return Collection
     */
    private function fetchNominations(bool $short): Collection
    {
        $vote = Vote::where('discord_id', $this->userId)
            ->where('month_id', $this->monthId)
            ->where('short', $short)
            ->first();

        $shortKey = (int)$short;

        if (!empty($this->currentOrder[$shortKey])) {
            return $this->getOrderedNominations($this->currentOrder[$shortKey], $shortKey);
        }

        if (empty($vote)) {
            return $this->getRandomNominations($shortKey);
        }

        $this->currentOrder[$shortKey] = $vote->rankings->sortBy('rank')->pluck('nomination_id')->toArray();

        return $vote->rankings->sortBy('rank')->map(function ($ranking) {
            return $ranking->nomination;
        });
    }
}
