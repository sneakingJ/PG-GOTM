<x-laravel-blade-sortable::sortable-item sort-key="{{ $nomination->id }}" class="card mb-4">
    <div class="card-content">
        <div class="media">
            <div class="media-left">
                <figure class="image igdb-cover">
                    <img src="{{ $nomination->game_cover }}">
                </figure>
            </div>
            <div class="media-content">
                <p class="title is-4">{{ $nomination->game_name }}</p>
                <p class="subtitle is-size-5 is-relative">
                <span class="pitch" onclick="this.classList.toggle('clicked');">{{ \Illuminate\Support\Str::limit($nomination->pitch, 50, '... ') }}
                    @if (\Illuminate\Support\Str::length($nomination->pitch) > 50)
                        <i class="fas fa-sort-down"></i><span class="fullpitch"><i class="fas fa-sort-up"></i> {{ \Illuminate\Support\Str::limit($nomination->pitch, 680, ' (...)') }}</span>
                    @endif
                </span><br><br>
                <a href="{{ $nomination->game_url }}" target="_blank" class="is-size-6"><span class="icon is-small"><i class="fas fa-external-link-alt" aria-hidden="true"></i></span> <span>IGDB</span></a>
                </p>
            </div>
        </div>
    </div>
</x-laravel-blade-sortable::sortable-item>
