<div class="voting">
    <div class="columns is-centered">
        <div class="column is-narrow has-text-centered">
            <h1 class="title is-2">Drag and Drop the games</h1>
            <h1 class="title is-4">to sort them in the priority you want them to win</h1>
            <p></p>
            <p>Please only vote for games you actually want to play next month :)</p>
        </div>
    </div>

    <div class="box">
        <div class="columns">
            <div class="column is-clearfix">
                <h2 class="title is-3">Long Games</h2>
                <x-laravel-blade-sortable::sortable group="long" name="long" :allow-drop="false" wire:onSortOrderChange="sortChange">
                    @each('livewire.cards.nominations-list-card', $longNominations, 'nomination')
                </x-laravel-blade-sortable::sortable>
                <button class="button is-pulled-left" type="button" wire:click="$emitTo('voting-list', 'saveVote', 'long')">Save Long</button>
                @livewire('vote-status', array('short' => false))
            </div>
            <div class="column is-narrow is-hidden-touch has-text-centered">
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
                <label class="checkbox mt-3"><input type="checkbox" wire:model="saveOnDrop"><br>Save on Drop</label>
            </div>
            <div class="column is-clearfix">
                <h2 class="title is-3">Short Games</h2>
                <x-laravel-blade-sortable::sortable group="short" name="short" :allow-drop="false" wire:onSortOrderChange="sortChange">
                    @each('livewire.cards.nominations-list-card', $shortNominations, 'nomination')
                </x-laravel-blade-sortable::sortable>
                <button class="button is-pulled-left" type="button" wire:click="$emitTo('voting-list', 'saveVote', 'short')">Save Short</button>
                @livewire('vote-status', array('short' => true))
            </div>
        </div>
    </div>

    @livewire('pitches-modal', array('editPossible' => false))
    <livewire:hltb-modal/>
</div>
