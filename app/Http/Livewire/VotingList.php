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
    public bool $saveOnDrop = true;

    /**
     * @var array
     */
    public array $currentOrder = array();

    /**
     * @var string
     */
    public string $divider = 'divider';

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->saveOnDrop = true;
    }

    /**
     * @param $short
     * @return bool
     */
    public function hasVotedGames($short): bool
    {
        $monthId = Month::where('status', MonthStatus::VOTING)->first()->id;

        $vote = Vote::where('month_id', $monthId)
            ->where('discord_id', $this->userId)
            ->where('short', $short)
            ->first();

        return $vote && Ranking::where('vote_id', $vote->id)->exists();
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

        $this->votedShort = $this->hasVotedGames(true);
        $this->votedLong = $this->hasVotedGames(false);

        return view('livewire.voting-list');
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
     * Button to instantly shift game from ranked / unranked sides of the collection
     */
    public function moveNomination($nominationId, $short): void
    {
        $shortKey = (int)$short;
        $currentOrder = $this->currentOrder[$shortKey];
        $dividerIndex = array_search($this->divider, $currentOrder);

        if (in_array($nominationId, array_slice($currentOrder, 0, $dividerIndex))) {
            // Move to unranked
            $rankedGames = array_diff(array_slice($currentOrder, 0, $dividerIndex), [$nominationId]);
            $unrankedGames = array_merge([$nominationId], array_slice($currentOrder, $dividerIndex + 1));
        } else {
            // Move to ranked
            $rankedGames = array_merge(array_slice($currentOrder, 0, $dividerIndex), [$nominationId]);
            $unrankedGames = array_diff(array_slice($currentOrder, $dividerIndex + 1), [$nominationId]);
        }

        $this->currentOrder[$shortKey] = array_merge($rankedGames, [$this->divider], $unrankedGames);

        if ($this->saveOnDrop) {
            $this->saveVote($short);
        }

        $this->votedShort = $this->hasVotedGames(true);
        $this->votedLong = $this->hasVotedGames(false);
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
            if ($nominationId == $this->divider) {
                break;
            }

            Ranking::create([
                'vote_id' => $vote->id,
                'nomination_id' => $nominationId,
                'rank' => $rank + 1,
            ]);
        }

        $this->votedShort = $this->hasVotedGames(true);
        $this->votedLong = $this->hasVotedGames(false);

        $this->emitTo('vote-status', 'updateVoteStatus', ['short' => $short, 'voted' => $this->hasVotedGames($short)]);
    }

    /**
     * @param bool $short
     * @return void
     */
    public function deleteVote(bool $short): void
    {
        $vote = Vote::where('month_id', $this->monthId)
            ->where('discord_id', $this->userId)
            ->where('short', $short)
            ->first();

        if (!empty($vote)) {
            Ranking::where('vote_id', $vote->id)->delete();
            $vote->delete();
        }

        $this->currentOrder[(int)$short] = [];

        $this->votedShort = $this->hasVotedGames(true);
        $this->votedLong = $this->hasVotedGames(false);

        $this->emitTo('vote-status', 'updateVoteStatus', ['short' => $short, 'voted' => false]);
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
        $this->currentOrder[$shortKey]->prepend($this->divider);

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

        $rankedNominations = $vote->rankings->sortBy('rank')->pluck('nomination_id')->toArray();
        $unrankedNominations = Nomination::where('month_id', $this->monthId)
            ->where('short', $short)
            ->whereNotIn('id', $rankedNominations)
            ->inRandomOrder()
            ->pluck('id')
            ->toArray();

        $this->currentOrder[$shortKey] = array_merge($rankedNominations, [$this->divider], $unrankedNominations);
        return Nomination::findMany(array_merge($rankedNominations, $unrankedNominations));
    }
}
