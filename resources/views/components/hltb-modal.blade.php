<div class="modal hltb-modal @if($active) is-active @endif">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">How Long To Beat</p>
            <button class="delete" aria-label="close" type="button" wire:click="$emitTo('hltb-modal', 'disableModal')"></button>
        </header>
        <section class="modal-card-body">
            <div class="game-list">
                @if(empty($results))
                    <article class="message is-warning mb-5">
                        <div class="message-header">
                            <p>Error</p>
                        </div>
                        <div class="message-body">
                            No HLTB entries found.
                        </div>
                    </article>
                @else
                    @foreach($results as $result)
                        <div class="card mb-4">
                            <div class="card-content">
                                <div class="media">
                                    <div class="media-left">
                                        <figure class="image igdb-cover">
                                            <img src="{{ $result['Image'] }}">
                                        </figure>
                                    </div>
                                    <div class="media-content">
                                        <p class="title is-4">{{ $result['Title'] }}</p>
                                        <p class="subtitle is-size-5">
                                            Main Story: {{ $result['Time']['Main Story'] ?? '' }}<br>
                                            Main + Extra: {{ $result['Time']['Main + Extra'] ?? '' }}<br>
                                            Completionist: {{ $result['Time']['Completionist'] ?? '' }}<br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </section>
        <footer class="modal-card-foot">
            <button class="button" type="button" wire:click="$emitTo('hltb-modal', 'disableModal')">Close</button>
        </footer>
    </div>
</div>
