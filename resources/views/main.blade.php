<div class="block">
    @if($nominationExists)
        <div class="columns is-centered">
            <div class="column is-narrow">
                <h1 class="title is-2">All current nominations</h1>
            </div>
        </div>
        <livewire:nomination-list/>
    @elseif($votingExists)
        <livewire:voting-result/>
    @else
        <div class="columns is-centered">
            <div class="column is-narrow">
                <h1 class="title is-2">Play the games</h1>
            </div>
        </div>
    @endif
</div>
