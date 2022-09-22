<div class="nomination-list">
    <div class="box" wire:poll.10000ms>
        <div class="columns">
            <div class="column is-half">
                <h2 class="title is-3">Long Games</h2>
                <div class="game-list">
                    @each('livewire.cards.nominations-list-card', $longNominations, 'nomination')
                </div>
            </div>
            <div class="column is-half">
                <h2 class="title is-3">Short Games</h2>
                <div class="game-list">
                    @each('livewire.cards.nominations-list-card', $shortNominations, 'nomination')
                </div>
            </div>
        </div>
    </div>

    @livewire('pitches-modal', array('editPossible' => true))
    <livewire:hltb-modal/>
</div>
