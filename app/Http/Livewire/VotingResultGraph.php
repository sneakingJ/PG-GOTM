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
        $rankings = $vote->rankings->keyBy('rank');
        while ($rankings->isNotEmpty()) {
            $rankings->shift();
            $rankings = $rankings->values()->map(function ($ranking, $index) {
                $ranking->rank = $index + 1;
                return $ranking;
            });

            if ($rankings->isNotEmpty()) {
                $vote->rankings = $rankings;
                $newTopChoice = $vote->rankings->first()->nomination_id;
                $newTopChoiceNomination = $remainingNominations->find($newTopChoice);

                if ($newTopChoiceNomination !== null) {
                    return $newTopChoiceNomination;
                }
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

        while ($nominations->count() > 1) {
            $loserKey = $nominations->pluck('id')->filter(function ($id) use ($currentVoteCount) {
                return isset($currentVoteCount[$id]);
            })->sort(function ($a, $b) use ($currentVoteCount) {
                return $currentVoteCount[$a] <=> $currentVoteCount[$b];
            })->first();

            $loser = $nominations->find($loserKey);
            if ($loser === null) {
                break;
            }

            $remainingNominations = $nominations->except($loserKey);

            $transferredVotes = [];
            foreach ($votes as $vote) {
                if ($vote->rankings->isEmpty()) {
                    continue;
                }

                if ($vote->rankings->first()->nomination_id != $loserKey) {
                    continue;
                }

                $newTopChoiceNomination = $this->getNextRankedNomination($vote, $remainingNominations);
                if ($newTopChoiceNomination !== null) {
                    $transferredVotes[$newTopChoiceNomination->id] = ($transferredVotes[$newTopChoiceNomination->id] ?? 0) + 1;
                }
            }

            foreach ($transferredVotes as $nominationId => $votesTransferred) {
                $nomination = $remainingNominations->find($nominationId);

                if ($nomination !== null) {
                    $currentVoteCountForWinner = $currentVoteCount[$nominationId] ?? 0;
                    $currentVoteCountForLoser = $currentVoteCount[$loserKey] ?? 0;

                    $currentVoteCount[$nominationId] += $votesTransferred;
                    $newVoteCountForWinner = $currentVoteCount[$nominationId];

                    $winnerSourceKey = "{$nomination->game_name} ({$currentVoteCountForWinner})";
                    $loserSourceKey = "{$loser->game_name} ({$currentVoteCountForLoser})";
                    $targetKey = "{$nomination->game_name} ({$newVoteCountForWinner})";

                    $voteFlow[$winnerSourceKey][] = [$targetKey, $currentVoteCountForWinner];
                    $voteFlow[$loserSourceKey][] = [$targetKey, $votesTransferred];
                }
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
