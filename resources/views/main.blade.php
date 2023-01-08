<div class="block">
    @if($nominationExists)
        <div class="columns is-centered">
            <div class="column is-narrow">
                <h1 class="title is-2">All current nominations</h1>
            </div>
        </div>
        <livewire:nomination-list/>
    @elseif($votingExists)
        <div class="columns is-centered">
            <div class="column is-narrow">
                <h1 class="title is-2 has-text-centered">Current Standings</h1>
                <p class="has-text-centered"><a href="#" wire:click="$emitTo('ranked-choice-modal', 'activateModal')">What is Ranked Choice Voting?</a></p>
            </div>
        </div>
        <livewire:voting-result :monthId="$monthIdVoting"/>
    @else
        <div class="columns is-centered">
            <div class="column is-narrow">
                <h1 class="title is-2 has-text-centered">Play the Games</h1>
                <p class="has-text-centered"><a href="#" wire:click="$emitTo('ranked-choice-modal', 'activateModal')">What is Ranked Choice Voting?</a></p>
            </div>
        </div>
        <livewire:voting-result :monthId="$monthIdPlaying"/>
    @endif

    <livewire:ranked-choice-modal/>
</div>
