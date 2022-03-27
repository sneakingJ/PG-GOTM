<?php

namespace App\Http\Livewire;

use App\Lib\MonthStatus;
use App\Models\Month;
use Livewire\Component;
use App\Models\Nomination;
use App\Models\Vote;

/**
 *
 */
class VotingResult extends Component
{

    /**
     * @var array
     */
    public array $shortResult;

    /**
     * @var array
     */
    public array $longResult;

    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $monthId = Month::where('status', MonthStatus::VOTING)->first()->id;

        $this->longResult = $this->prepareChartData($monthId, false);
        $this->shortResult = $this->prepareChartData($monthId,true);

        return view('livewire.voting-result');
    }

    /**
     * @param $monthId
     * @param $short
     * @return array
     */
    private function prepareChartData($monthId, $short): array
    {
        $nominations = Nomination::where('month_id', $monthId)->where('jury_selected', true)->where('short', $short)->get();
        $votes = Vote::where('month_id', $monthId)->where('short', $short)->get();
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

        // One game has the majority of votes
        if ($totalAmountVotes / max($voteCount) <= 2) {
            $winnerId = array_search(max($voteCount), $voteCount);
            $winner = $nominations->find($winnerId);

            foreach ($nominations as $fromNomination) {
                $amountVotes = ($voteCount[$fromNomination->id] > 0) ? $voteCount[$fromNomination->id] : 0.1;

                if ($short) {
                    $returnData[] = [$winner->game_name . ' (' . $totalAmountVotes . ') ', $fromNomination->game_name . ' (' . floor($amountVotes) . ')', $amountVotes];
                    continue;
                }

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

            if ($short) {
                $returnData[] = [$winner->game_name . ' (' . $amountVotesTo[$winner->id] . ') ', $loser->game_name . ' (' . $voteCount[$loserId] . ')', $amountGain];
                $returnData[] = [$winner->game_name . ' (' . $amountVotesTo[$winner->id] . ') ', $winner->game_name . ' (' . $amountVotesFrom . ')', $amountVotesFrom];
                continue;
            }

            $returnData[] = [$loser->game_name . ' (' . $voteCount[$loserId] . ')', $winner->game_name . ' (' . $amountVotesTo[$winner->id] . ') ', $amountGain];
            $returnData[] = [$winner->game_name . ' (' . $amountVotesFrom . ')', $winner->game_name . ' (' . $amountVotesTo[$winner->id] . ') ', $amountVotesFrom];
        }

        $ultimateWinnerKey = array_search(max($amountVotesTo), $amountVotesTo);
        $ultimateWinner = $nominations->find($ultimateWinnerKey);
        foreach ($winners as $winner) {
            if ($short) {
                $returnData[] = [$ultimateWinner->game_name . ' (' . $totalAmountVotes . ')  ', $winner->game_name . ' (' . $amountVotesTo[$winner->id] . ') ', $amountVotesTo[$winner->id]];
                continue;
            }

            $returnData[] = [$winner->game_name . ' (' . $amountVotesTo[$winner->id] . ') ', $ultimateWinner->game_name . ' (' . $totalAmountVotes . ')  ', $amountVotesTo[$winner->id]];
        }

        return $returnData;
    }
}
