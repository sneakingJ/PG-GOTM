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
            // Vertex names: $nominationId_$round
            $vertex = $graph->createVertex($nomination->id . '_' . $round);
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
            $currentVoteCount = $this->getCurrentVoteCount($votes, $originalNominations);

            // Sort all nominations by their vote count, then by their weighted score
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
                $votesTransferred = $transferredVotes[$winnerId] ?? 0;

                $nextRound = $round + 1;
                if ($graph->hasVertex($winnerId . '_' . $nextRound)) {
                    $nextRoundWinnerVertex = $graph->getVertex($winnerId . '_' . $nextRound);
                } else {
                    $nextRoundWinnerVertex = $graph->createVertex($winnerId . '_' . $nextRound);
                    $nextRoundWinnerVertex->setAttribute('votes', $currentVoteCount[$winnerId]);
                }

                $newVoteCount = $nextRoundWinnerVertex->getAttribute('votes') + $votesTransferred;
                $nextRoundWinnerVertex->setAttribute('votes', $newVoteCount);

                $loserVertex = $graph->getVertex($loser->id . '_' . $round);

                // Create edge to show vote transfer in reverse order (loser to nomination)
                $loserVertex->createEdgeTo($nextRoundWinnerVertex)->setWeight($votesTransferred);

                // Create edge to show self vote transfer
                $currentRoundWinnerVertex = $graph->getVertex($winnerId . '_' . $round);
                $currentRoundWinnerVertex->createEdgeTo($nextRoundWinnerVertex)->setWeight($currentRoundWinnerVertex->getAttribute('votes'));
            }

            $nominations = $remainingNominations;
            $round++;
        }

        $results = [];
        foreach ($graph->getVertices() as $vertex) {
            foreach ($vertex->getEdgesOut() as $edge) {
                $source = $edge->getVertexStart();
                $target = $edge->getVertexEnd();
                $weight = $edge->getWeight();

                // $roundNumber_$gameName ($currentVotes)
                $sourceRoundNumber = explode('_', $source->getId())[1];
                $sourceNominationId = explode('_', $source->getId())[0];
                $sourceGameName = Nomination::find($sourceNominationId)->game_name;

                $targetRoundNumber = explode('_', $target->getId())[1];
                $targetNominationId = explode('_', $target->getId())[0];
                $targetGameName = Nomination::find($targetNominationId)->game_name;

                $results[] = [
                    "Round $sourceRoundNumber: " . $sourceGameName . ' (' . $source->getAttribute('votes') . ")",
                    "Round $targetRoundNumber: " . $targetGameName . ' (' . $target->getAttribute('votes') . ")",
                    $weight
                ];
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
