<div>
    <div class="columns is-centered">
        <div class="column is-narrow">
            <h1 class="title is-2">All current nominations</h1>
        </div>
    </div>

    <div class="box game-list" wire:poll>
        @foreach($nominations as $nomination)
            <div class="card mb-4">
                <div class="card-content">
                    <div class="media">
                        <div class="media-left">
                            <figure class="image igdb-cover">
                                <img src="{{ $nomination->game_cover }}">
                            </figure>
                        </div>
                        <div class="media-content">
                            <p class="title is-4">{{ $nomination->game_name }}</p>
                            <p class="subtitle is-size-5">{{ \Illuminate\Support\Str::limit($nomination->pitch, 680, ' (...)') }}<br><br>
                                <a href="{{ $nomination->game_url }}" target="_blank" class="is-size-6"><span class="icon is-small"><i class="fas fa-external-link-alt" aria-hidden="true"></i></span> <span>IGDB</span></a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
