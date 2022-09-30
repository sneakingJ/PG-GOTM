<div>
    <h3>{{ $monthStatus->value }}</h3>

    @switch ($monthStatus)
        @case (\App\Lib\MonthStatus::VOTING)
            <button class="button" type="button" wire:click="votingToPlaying">Close Voting and start Playing</button>
            @break
        @default
            Nothing to do
    @endswitch
</div>
