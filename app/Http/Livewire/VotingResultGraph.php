<?php

namespace App\Http\Livewire;

use App\Models\Nomination;
use App\Models\Vote;
use Fhaculty\Graph\Edge\Directed;
use Livewire\Component;
use Fhaculty\Graph\Graph;
use Fhaculty\Graph\Vertex;

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
        $graph = new Graph();
        $originalNominations = $nominations;
        $currentVoteCount = $this->getCurrentVoteCount($votes, $nominations);

        $round = 1;

        foreach ($nominations as $nomination) {
            $vertexId = $nomination->game_name . '_' . $round;
            $vertex = $graph->createVertex($vertexId);
            $vertex->setAttribute('votes', $currentVoteCount[$nomination->id]);
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

            $potentialLosers = $nominations->sortByDesc(function ($nomination) use ($currentVoteCount, $nominationWeightedScores) {
                return [$currentVoteCount[$nomination->id], $nominationWeightedScores[$nomination->id]];
            });

            $loser = $potentialLosers->last();
            if ($loser === null) {
                break;
            }

            $loserKey = $loser->id;
            $remainingNominations = $nominations->except($loserKey);

            $transferredVotes = [];
            foreach ($votes as $vote) {
                if ($vote->rankings->count() > 0 && $vote->rankings->first()->nomination_id != $loserKey) {
                    continue;
                }

                $newTopChoiceNomination = $this->getNextRankedNomination($vote, $remainingNominations);
                if ($newTopChoiceNomination !== null) {
                    $transferredVotes[$newTopChoiceNomination->id] = ($transferredVotes[$newTopChoiceNomination->id] ?? 0) + 1;
                }
            }

            foreach ($remainingNominations as $nomination) {
                $winnerId = $nomination->id;
                $winnerName = $nomination->game_name;
                $votesTransferred = $transferredVotes[$winnerId] ?? 0;

                $nextRoundVertexId = $winnerName . '_' . ($round + 1);
                if ($graph->hasVertex($nextRoundVertexId)) {
                    $nextRoundWinnerVertex = $graph->getVertex($nextRoundVertexId);
                } else {
                    $nextRoundWinnerVertex = $graph->createVertex($nextRoundVertexId);
                }
                $nextRoundWinnerVertex->setAttribute('votes', $currentVoteCount[$winnerId] + $votesTransferred);

                $loserVertex = $graph->getVertex($loser->game_name . '_' . $round);
                $loserVertex->createEdgeTo($nextRoundWinnerVertex)->setWeight($votesTransferred);

                $currentRoundWinnerVertex = $graph->getVertex($winnerName . '_' . $round);
                $currentRoundWinnerVertex->createEdgeTo($nextRoundWinnerVertex)->setWeight($currentVoteCount[$winnerId]);
            }

            $nominations = $remainingNominations;
            $currentVoteCount = $this->getCurrentVoteCount($votes, $originalNominations);
            $round++;
        }

        $results = [];
        foreach ($graph->getEdges() as $edge) {
            $source = $edge->getVertexStart();
            $target = $edge->getVertexEnd();
            $weight = $edge->getWeight();

            $sourceRoundNumber = explode('_', $source->getId())[1];
            $sourceGameName = explode('_', $source->getId())[0];

            $targetRoundNumber = explode('_', $target->getId())[1];
            $targetGameName = explode('_', $target->getId())[0];

            $results[] = [
                $sourceGameName . ' (' . $source->getAttribute('votes') . ")" . str_repeat(' ', $sourceRoundNumber),
                $targetGameName . ' (' . $target->getAttribute('votes') . ")" . str_repeat(' ', $targetRoundNumber),
                $weight
            ];
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
