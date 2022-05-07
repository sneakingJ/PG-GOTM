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

    @if(!empty($nomination) && $pitchModalActive)
        <div class="modal is-active">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">{!! html_entity_decode($nomination->game_name) !!}</p>
                    <button class="delete" aria-label="close" type="button" wire:click="$emitTo('nomination-list', 'disableModal')"></button>
                </header>
                <section class="modal-card-body">
                    <div class="columns">
                        <div class="column is-one-fifth">
                            <div class="igdb-cover">
                                <img src="{{ $nomination->game_cover }}">
                            </div>
                        </div>
                        <div class="column is-four-fifths">
                            @include('livewire.snippets.nomination-list-pitches', ['pitches' => $nomination->pitches()->get()])
                        </div>
                    </div>
                </section>
                <footer class="column modal-card-foot">
                    <button class="button is-pulled-left" type="button" wire:click="$emitTo('nomination-list', 'addPitch')">
                        @if($userPitch->exists) Edit pitch @else Add pitch @endif
                    </button>
                    <button class="button is-pulled-right" type="button" wire:click="$emitTo('nomination-list', 'disableModal')">Close</button>
                </footer>
            </div>
        </div>

        <div class="modal @if($newPitchModalActive) is-active @endif">
            <div class="modal-background"></div>
            <form wire:submit.prevent="savePitch">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Your pitch</p>
                        <button class="delete" aria-label="close" type="button" wire:click="$emitTo('nomination-list', 'closeNewPitchModal')"></button>
                    </header>
                    <section class="modal-card-body">
                        <div class="field">
                            <div class="control">
                                <textarea class="textarea" wire:model.defer="userPitch.pitch" placeholder="Add your own pitch to this nomination! These are optional but will help the Jury out quite a bit. All pitches will be shown publicly." maxlength="1000">{{ $userPitch->pitch }}</textarea>
                            </div>
                        </div>
                    </section>
                    <footer class="column modal-card-foot">
                        <button class="button is-pulled-left" type="submit">Save</button>
                        <button class="button is-pulled-right" type="button" wire:click="$emitTo('nomination-list', 'closeNewPitchModal')">Close</button>
                    </footer>
                </div>
            </form>
        </div>
    @endif
</div>
