<div class="voting">
    <div class="columns is-centered">
        <div class="column is-narrow">
            <h1 class="title is-2">Drag and Drop the games to rank them</h1>
        </div>
    </div>

    <div class="box">
        <div class="columns">
            <div class="column">
                <h2 class="title is-3">Long Games</h2>
                <x-laravel-blade-sortable::sortable group="long" name="long" :allow-drop="false" wire:onSortOrderChange="sortChange">
                    @each('livewire.cards.voting-list-card', $longNominations, 'nomination')
                </x-laravel-blade-sortable::sortable>
            </div>
            <div class="column is-narrow is-hidden-mobile">
                <h2 class="title is-3">&nbsp;</h2>
                <div class="voting-number">
                    1
                </div>
                <div class="voting-number">
                    2
                </div>
                <div class="voting-number">
                    3
                </div>
            </div>
            <div class="column">
                <h2 class="title is-3">Short Games</h2>
                <x-laravel-blade-sortable::sortable group="short" name="short" :allow-drop="false" wire:onSortOrderChange="sortChange">
                    @each('livewire.cards.voting-list-card', $shortNominations, 'nomination')
                </x-laravel-blade-sortable::sortable>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <button class="button" type="button" wire:click="$emitTo('voting-list', 'saveVote', 'long')">Save</button>
            </div>
            <div class="column is-narrow has-text-centered">
                <label class="checkbox"><input type="checkbox" wire:model="saveOnDrop"><br>Save on Drop</label>
            </div>
            <div class="column">
                <button class="button is-pulled-right" type="button" wire:click="$emitTo('voting-list', 'saveVote', 'short')">Save</button>
            </div>
        </div>
    </div>
    <div class="modal @if($pitchModalActive) is-active @endif">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">{{ $modalName }}</p>
                <button class="delete" aria-label="close" type="button" wire:click="$emitTo('voting-list', 'disableModal')"></button>
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
                <button class="button" type="button" wire:click="$emitTo('voting-list', 'disableModal')">Close</button>
            </footer>
        </div>
    </div>
</div>
