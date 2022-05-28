<div>
    <div class="modal @if($active) is-active @endif">
        @isset($nomination)
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">{!! html_entity_decode($nomination->game_name) !!}</p>
                    <button class="delete" aria-label="close" type="button" wire:click="$emitTo('pitches-modal', 'disableModal')"></button>
                </header>
                <section class="modal-card-body">
                    <div class="columns">
                        <div class="column is-one-fifth">
                            <div class="igdb-cover">
                                <img src="{{ $nomination->game_cover }}">
                            </div>
                        </div>
                        <div class="column is-four-fifths">
                            @include('livewire.snippets.pitches-list', ['pitches' => $nomination->pitches()->orderBy('created_at')->get()])
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    @if($editPossible)
                        <button class="button is-pulled-left" type="button" wire:click="$emitTo('pitches-modal', 'addPitch')">
                            @if($userPitch->exists) Edit pitch @else Add pitch @endif
                        </button>
                    @endif
                    <button class="button is-pulled-right" type="button" wire:click="$emitTo('pitches-modal', 'disableModal')">Close</button>
                </footer>
            </div>
        @endisset
    </div>

    @if($newPitchModalActive)
        <div class="modal is-active">
            <div class="modal-background"></div>
            <form wire:submit.prevent="savePitch">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Your pitch</p>
                        <button class="delete" aria-label="close" type="button" wire:click="$emitTo('pitches-modal', 'closeNewPitchModal')"></button>
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
                        <button class="button is-pulled-right" type="button" wire:click="$emitTo('pitches-modal', 'closeNewPitchModal')">Close</button>
                    </footer>
                </div>
            </form>
        </div>
    @endif
</div>
