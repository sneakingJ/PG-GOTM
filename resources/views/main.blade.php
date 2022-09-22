<div class="block">
    @if($juryExists)
        <div class="columns is-centered">
            <div class="column is-narrow">
                <h1 class="title is-2">Jury is currently discussing these nominations</h1>
            </div>
        </div>
        <livewire:nomination-list/>
    @elseif($nominationExists)
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
