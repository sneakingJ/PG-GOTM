<div class="nomination-list">
    <div class="columns is-centered">
        <div class="column is-narrow">
            <h1 class="title is-2">All current nominations</h1>
        </div>
    </div>

    <div class="box" wire:poll.10000ms>
        <div class="columns">
            <div class="column is-half">
                <h2 class="title is-3">Long Games</h2>
                <div class="game-list">
                    @each('livewire.cards.nomination-list-card', $longNominations, 'nomination')
                </div>
            </div>
            <div class="column is-half">
                <h2 class="title is-3">Short Games</h2>
                <div class="game-list">
                    @each('livewire.cards.nomination-list-card', $shortNominations, 'nomination')
                </div>
            </div>
        </div>
    </div>

    <div class="modal @if($pitchModalActive) is-active @endif">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">{{ $modalName }}</p>
                <button class="delete" aria-label="close" type="button" wire:click="$emitTo('nomination-list', 'disableModal')"></button>
            </header>
            <section class="modal-card-body">
                <div class="columns">
                    <div class="column is-one-fifth">
                        <div class="igdb-cover">
                            <img src="{{ $modalCover }}">
                        </div>
                    </div>
                    <div class="column is-four-fifths">
                        {!! $modalPitch !!}
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" wire:click="$emitTo('nomination-list', 'disableModal')">Close</button>
            </footer>
        </div>
    </div>
</div>
