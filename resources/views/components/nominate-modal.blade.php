<div class="modal @if($active) is-active @endif">
    <div class="modal-background"></div>
    <form wire:submit.prevent="nominate">
        <input type="hidden" wire:model="gameId" value="{{ $gameId }}">
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">{{ $gameName }}</p>
                <button class="delete" aria-label="close" type="button" wire:click="$emitTo('nominate-modal', 'disableModal')"></button>
            </header>
            <section class="modal-card-body">
                <div class="columns">
                    <div class="column is-one-fifth">
                        <div class="igdb-cover">
                            <img src="{{ $gameCover }}">
                        </div>
                    </div>
                    <div class="column is-four-fifths">
                        <div class="control">
                            <textarea class="textarea" wire:model.defer="gamePitch" placeholder="Pitch us your nomination! These are optional but will help the Jury out quite a bit. All pitches will be shown publicly." maxlength="1000">{{ $gamePitch }}</textarea>
                        </div>
                        <div class="control is-pulled-left mt-4">
                            <label class="radio">
                                <input type="radio" name="short" value="1" wire:model="gameShort">
                                Short Game (<= 12h HLTB Main Story)
                            </label><br>
                            <label class="radio">
                                <input type="radio" name="short" value="0" wire:model="gameShort">
                                Long Game (> 12h HLTB Main Story)
                            </label>
                        </div>
                        <button type="button" class="button is-pulled-right mt-5" wire:click="$emitTo('hltb-modal', 'activateModal', '{{ $gameName }}')" onclick="buttonLoad(this);">Get HLTB times</button>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success" type="submit">Submit nomination</button>
                <button class="button" type="button" wire:click="$emitTo('nominate-modal', 'disableModal')">Cancel</button>
            </footer>
        </div>
    </form>
</div>
