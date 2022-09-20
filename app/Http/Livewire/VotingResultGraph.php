<?php

namespace App\Http\Livewire;

use App\Lib\MonthStatus;
use App\Models\Month;
use App\Models\Nomination;
use App\Models\Vote;
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

    /**
     * @return array
     */
    private function fetchVotingResults(): array
    {
        $monthId = Month::where('status', MonthStatus::VOTING)->first()->id;

        $nominations = Nomination::where('month_id', $monthId)->where('jury_selected', true)->where('short', $this->short)->get();
        $votes = Vote::where('month_id', $monthId)->where('short', $this->short)->get();
        $totalAmountVotes = $votes->count();

        if ($totalAmountVotes === 0) {
            return array();
        }

        $voteCount = array();
        foreach ($nominations as $nomination) {
            $voteCount[$nomination->id] = Vote::where('rank_1', $nomination->id)->count();
        }

        $returnData = array();

        // Same amount of votes for all three games
        if (count(array_flip($voteCount)) === 1) {
            $amountVotes = $totalAmountVotes / count($voteCount);

            return array(
                [$nominations->first()->game_name . ' (' . $amountVotes . ')', $nominations->skip(1)->first()->game_name . ' (' . $amountVotes . ')', 1],
                [$nominations->skip(1)->first()->game_name . ' (' . $amountVotes . ')', $nominations->last()->game_name . ' (' . $amountVotes . ')', 1]
            );
        }

        $firstRoundWinnerKey = array_search(max($voteCount), $voteCount);

        // One game has the majority of votes
        if ($totalAmountVotes / max($voteCount) <= 2) {
            $winner = $nominations->find($firstRoundWinnerKey);

            foreach ($nominations as $fromNomination) {
                $amountVotes = ($voteCount[$fromNomination->id] > 0) ? $voteCount[$fromNomination->id] : 0.1;

                $returnData[] = [$fromNomination->game_name . ' (' . floor($amountVotes) . ')', $winner->game_name . ' (' . $totalAmountVotes . ') ', $amountVotes];
            }

            return $returnData;
        }

        // No majority but one clear loser
        $loserId = array_search(min($voteCount), $voteCount);
        $loser = $nominations->find($loserId);
        $winners = $nominations->except($loserId);
        $amountVotesTo = array();
        foreach ($winners as $winner) {
            $amountVotesFrom = $voteCount[$winner->id];
            $amountGain = Vote::where('rank_1', $loserId)->where('rank_2', $winner->id)->count();
            $amountVotesTo[$winner->id] = $amountVotesFrom + $amountGain;

            $returnData[] = [$loser->game_name . ' (' . $voteCount[$loserId] . ')', $winner->game_name . ' (' . $amountVotesTo[$winner->id] . ') ', $amountGain];
            $returnData[] = [$winner->game_name . ' (' . $amountVotesFrom . ')', $winner->game_name . ' (' . $amountVotesTo[$winner->id] . ') ', $amountVotesFrom];
        }

        if ($amountVotesTo[$winners->first()->id] === $amountVotesTo[$winners->last()->id]) { // Tie in second round
            $ultimateWinnerKey = $firstRoundWinnerKey;
        } else {
            $ultimateWinnerKey = array_search(max($amountVotesTo), $amountVotesTo);
        }
        $ultimateWinner = $nominations->find($ultimateWinnerKey);
        foreach ($winners as $winner) {
            $returnData[] = [$winner->game_name . ' (' . $amountVotesTo[$winner->id] . ') ', $ultimateWinner->game_name . ' (' . $totalAmountVotes . ')  ', $amountVotesTo[$winner->id]];
        }

        return $returnData;
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
