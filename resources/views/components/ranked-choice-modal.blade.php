<div class="modal @if($active) is-active @endif">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">What is Ranked Choice Voting?</p>
            <button class="delete" aria-label="close" type="button" wire:click="$emitTo('ranked-choice-modal', 'disableModal')"></button>
        </header>
        <section class="modal-card-body has-text-centered">
            @if($active)
                <iframe width="560" height="315" src="https://www.youtube.com/embed/oHRPMJmzBBw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            @endif
        </section>
        <footer class="column modal-card-foot">
            <button class="button is-pulled-right" type="button" wire:click="$emitTo('ranked-choice-modal', 'disableModal')">Close</button>
        </footer>
    </div>
</div>
