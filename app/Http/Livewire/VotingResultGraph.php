<?php

namespace App\Http\Livewire;

use App\Models\Nomination;
use App\Models\Vote;
use Livewire\Component;

class VotingResultGraph extends Component
{
    /**
     * @var bool
     */
    public bool $short;

    /**
     * @var int
     */
    public int $monthId;

    /**
     * @var string
     */
    public string $categoryName;

    /**
     * @var array
     */
    public array $results;

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->categoryName = $this->short ? 'Short' : 'Long';

        $this->poll();

        return view('components.voting-result-graph');
    }

    /**
     * @return void
     */
    public function poll(): void
    {
        $results = $this->fetchVotingResults();
        $this->results = $this->prepareChartData($results);

        $this->dispatchBrowserEvent('polled');
    }

    private function fetchVotingResults(): array
    {
        $nominations = Nomination::where('month_id', $this->monthId)
            ->where('jury_selected', true)
            ->where('short', $this->short)
            ->get();

        $votes = Vote::where('month_id', $this->monthId)
            ->where('short', $this->short)
            ->with('rankings')
            ->get();

        if ($votes->isEmpty()) {
            return [];
        }

        $voteCount = $this->getCurrentVoteCount($votes, $nominations);

        $nominationsWithVotes = $nominations->filter(function ($nomination) use ($voteCount) {
            return $voteCount[$nomination->id] > 0;
        });

        if ($nominationsWithVotes->isEmpty()) {
            return [];
        }

        return $this->runRounds($nominationsWithVotes, $votes);
    }

    /**
     * Get the current vote count for each nomination where rank = 1
     * @param $votes
     * @param $nominations
     * @return array
     */
    private function getCurrentVoteCount($votes, $nominations): array
    {
        $voteCount = $nominations->mapWithKeys(function ($nomination) {
            return [$nomination->id => 0];
        })->toArray();

        foreach ($votes as $vote) {
            $firstRanking = $vote->rankings->where('rank', 1)->first();
            if ($firstRanking) {
                $voteCount[$firstRanking->nomination_id]++;
            }
        }

        return $voteCount;
    }

    private function getNextRankedNomination($vote, $remainingNominations)
    {
        foreach ($vote->rankings as $ranking) {
            $nominationId = $ranking->nomination_id;
            if ($remainingNominations->contains('id', $nominationId)) {
                return $remainingNominations->firstWhere('id', $nominationId);
            }
        }

        return null;
    }

    private function runRounds($nominations, $votes): array
    {
        $voteFlow = [];
        $currentVoteCount = $this->getCurrentVoteCount($votes, $nominations);

        foreach ($nominations as $nomination) {
            $key = "{$nomination->game_name} ({$currentVoteCount[$nomination->id]})";
            $voteFlow[$key] = [];
        }

        $maxRank = $nominations->count();
        $rankWeights = array_reverse(range(1, $maxRank));

        $nominationWeightedScores = $nominations->mapWithKeys(function ($nomination) use ($votes, $rankWeights) {
            $weightedScore = $votes->sum(function ($vote) use ($nomination, $rankWeights) {
                $ranking = $vote->rankings->firstWhere('nomination_id', $nomination->id);
                return $ranking ? $rankWeights[$ranking->rank - 1] ?? 0 : 0;
            });

            return [$nomination->id => $weightedScore];
        });

        while ($nominations->count() > 1) {
            $lowestVoteCount = min($currentVoteCount);
            $potentialLosers = $nominations->filter(function ($nomination) use ($currentVoteCount, $lowestVoteCount) {
                return $currentVoteCount[$nomination->id] === $lowestVoteCount;
            });

            if ($potentialLosers->count() === $nominations->count()) {
                $potentialLosers = $potentialLosers->sortByDesc(function ($nomination) use ($nominationWeightedScores) {
                    return $nominationWeightedScores[$nomination->id];
                });
            }
            $loser = $potentialLosers->first();

            if ($loser === null) {
                break;
            }

            $loserKey = $loser->id;
            $remainingNominations = $nominations->except($loserKey);

            $transferredVotes = collect();
            $eliminatedVotes = collect();

            $votes->each(function ($vote) use ($loserKey, $remainingNominations, &$transferredVotes, &$eliminatedVotes) {
                if ($vote->rankings->isEmpty() || $vote->rankings->first()->nomination_id != $loserKey) {
                    return;
                }

                $newTopChoiceNomination = $this->getNextRankedNomination($vote, $remainingNominations);
                if ($newTopChoiceNomination !== null) {
                    $transferredVotes->put($newTopChoiceNomination->id, $transferredVotes->get($newTopChoiceNomination->id, 0) + 1);
                } else {
                    $eliminatedVotes->push($vote);
                }
            });

            $transferredVotes->each(function ($votesTransferred, $nominationId) use (&$currentVoteCount, $remainingNominations, &$voteFlow, $loser) {
                $nomination = $remainingNominations->find($nominationId);
                if ($nomination !== null) {
                    $currentVoteCountForWinner = $currentVoteCount[$nominationId] ?? 0;
                    $currentVoteCountForLoser = $currentVoteCount[$loser->id] ?? 0;

                    $currentVoteCount[$nominationId] += $votesTransferred;
                    $newVoteCountForWinner = $currentVoteCount[$nominationId];

                    $voteFlow["{$nomination->game_name} ({$currentVoteCountForWinner})"][] = ["{$nomination->game_name} ({$newVoteCountForWinner})", $currentVoteCountForWinner];
                    $voteFlow["{$loser->game_name} ({$currentVoteCountForLoser})"][] = ["{$nomination->game_name} ({$newVoteCountForWinner})", $votesTransferred];
                }
            });

            if ($eliminatedVotes->isNotEmpty()) {
                $voteFlow["{$loser->game_name} ({$currentVoteCount[$loserKey]})"][] = ["Eliminated", $eliminatedVotes->count()];
            }

            $nominations = $remainingNominations;
        }

        $results = [];
        foreach ($voteFlow as $source => $data) {
            foreach ($data as $target) {
                $results[] = [$source, $target[0], $target[1]];
            }
        }

        // If $result is empty but voteFlow is not that means we have a tie
        if (empty($results) && !empty($voteFlow)) {
            foreach ($voteFlow as $source => $data) {
                $results[] = [$source, 'Tie', 1];
            }
        }

        return $results;
    }


    /**
     * @param array $results
     * @return array
     */
    private function prepareChartData(array $results): array
    {
        return array_map(function ($result) {
            return [addslashes($result[0]), addslashes($result[1]), $result[2]];
        }, $results);
    }
}
