<?php

namespace App\Http\Livewire;

use App\Lib\MonthStatus;
use App\Models\Month;
use App\Models\Nomination;
use App\Models\Vote;
use App\Models\Ranking;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

/**
 *
 */
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
        $nominations = Nomination::where('month_id', $this->monthId)->where('jury_selected', true)->where('short', $this->short)->get();
        $votes = Vote::where('month_id', $this->monthId)->where('short', $this->short)->with('rankings')->get();
        $totalAmountVotes = $votes->count();

        if ($totalAmountVotes === 0) {
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
     * @return array
     */
    private function getCurrentVoteCount($votes, $nominations): array
    {
        // Initialize vote count for all nominations to 0
        $voteCount = [];
        foreach ($nominations as $nomination) {
            $voteCount[$nomination->id] = 0;
        }

        // Count the votes for each nomination where rank = 1
        foreach ($votes as $vote) {
            $rankings = $vote->rankings->where('rank', 1);
            $nominationId = $rankings->first()->nomination_id;

            if (!isset($voteCount[$nominationId])) {
                $voteCount[$nominationId] = 0;
            }

            $voteCount[$nominationId]++;
        }

        return $voteCount;
    }

    private function get_next_ranked_nomination($vote, $remainingNominations)
    {
        $newTopChoiceNomination = null;
        $rankings = $vote->rankings->keyBy('rank');

        while ($newTopChoiceNomination == null && $rankings->count() > 0) {
            // Remove the top rank (rank 1)
            $rankings->shift();

            // Re-index ranks starting from 1
            $rankings = $rankings->values()->map(function ($ranking, $index) {
                $ranking->rank = $index + 1;
                return $ranking;
            });

            if ($rankings->count() > 0) {
                $vote->rankings = $rankings;

                // Get the new top choice after removing the loser
                $newTopChoice = $vote->rankings->first()->nomination_id;
                $newTopChoiceNomination = $remainingNominations->find($newTopChoice);

                if ($newTopChoiceNomination !== null) {
                    return $newTopChoiceNomination;
                }
            } else {
                break; // Exit loop if no more rankings are available
            }
        }

        return null;
    }


    /**
     * Run the rounds of the voting process
     * @param $nominations
     * @param $votes
     * @return array
     */
    private function runRounds($nominations, $votes): array
    {
        $voteFlow = [];
        $round = 0;
        $currentVoteCount = $this->getCurrentVoteCount($votes, $nominations);

        // Initialize voteFlow with the initial vote counts
        foreach ($nominations as $nomination) {
            $voteCount = $currentVoteCount[$nomination->id] ?? 0;
            $key = $nomination->game_name . ' (' . $voteCount . ')';
            // voteflow[key] = [[name, weight]
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

            $oldVotes = $currentVoteCount[$loserKey];
            Log::info("Round $round: $loser->game_name eliminated with $oldVotes votes. Votes transferred to:");

            // Reallocate votes from the eliminated candidate
            $transferredVotes = [];
            foreach ($votes as $vote) {
                // Check if this vote's top choice is the loser
                if ($vote->rankings->first()->nomination_id != $loserKey) {
                    continue;
                }

                $newTopChoiceNomination = $this->get_next_ranked_nomination($vote, $remainingNominations);

                if ($newTopChoiceNomination !== null) {
                    $transferredVotes[$newTopChoiceNomination->id] = ($transferredVotes[$newTopChoiceNomination->id] ?? 0) + 1;
                }
            }

            foreach ($transferredVotes as $nominationId => $votesTransferred) {
                $nomination = $remainingNominations->find($nominationId);

                if ($nomination !== null) {
                    $currentVoteCountForWinner = $currentVoteCount[$nominationId] ?? 0;
                    $currentVoteCountForLoser = $currentVoteCount[$loserKey] ?? 0;

                    $currentVoteCount[$nominationId] = ($currentVoteCount[$nominationId] ?? 0) + $votesTransferred;
                    $newVoteCountForWinner = $currentVoteCount[$nominationId];

                    $winnerSourceKey = $nomination->game_name . ' (' . $currentVoteCountForWinner . ')';
                    $loserSourceKey = $loser->game_name . ' (' . $currentVoteCountForLoser . ')';
                    $targetKey = $nomination->game_name . ' (' . $newVoteCountForWinner . ')';

                    $voteFlow[$winnerSourceKey][] = [
                        $targetKey,
                        $currentVoteCountForWinner
                    ];
                    $voteFlow[$loserSourceKey][] = [
                        $targetKey,
                        $votesTransferred,
                    ];

                }
            }

            $nominations = $remainingNominations;
            $round++;
        }

        // Source, Target, Weight
        // [ 'A', 'X', 5 ],
        // [ 'A', 'Y', 7 ],
        // [ 'A', 'Z', 6 ],
        // [ 'B', 'X', 2 ],
        // [ 'B', 'Y', 9 ],
        // [ 'B', 'Z', 4 ]
        $results = [];
        foreach ($voteFlow as $source => $data) {
            foreach ($data as $target) {
                $results[] = [$source, $target[0], $target[1]];
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
        $chartArray = array();

        foreach ($results as $result) {
            $chartArray[] = array(addslashes($result[0]), addslashes($result[1]), $result[2]);
        }

        return $chartArray;
    }
}
