<x-laravel-blade-sortable::sortable-item sort-key="{{ $nomination->id }}" class="card mb-4">
    <div class="card">
        <div class="card-content">
            <div class="columns is-mobile">
                <div class="column is-narrow">
                    <figure class="image is-96x96">
                        <img src="{{ $nomination->game_cover }}" alt="{{ $nomination->game_name }}">
                    </figure>
                </div>
                <div class="column">
                    <p class="title is-5">{{ $nomination->game_name }}</p>
                    <p class="subtitle is-size-5 is-relative">
                        @php
                            $firstPitch = $nomination->pitches()->orderBy('created_at')->first();
                        @endphp
                        <span class="pitch"
                            wire:click="$emitTo('pitches-modal', 'activateModal', '{{ $nomination->id }}')">
                            @if (!empty($firstPitch))
                                {{ \Illuminate\Support\Str::limit($firstPitch->pitch, 50, '... ') }}
                                <i class="fas fa-pen"></i>
                            @else
                                <i class="fas fa-pen"></i>
                            @endif
                    </p>

                    <div class="mt-4">
                        <a href="{{ $nomination->game_url }}" target="_blank" class="is-size-6"><span
                                class="icon is-small"><i class="fas fa-external-link-alt" aria-hidden="true"></i></span>
                            <span>IGDB</span></a>
                    </div>
                </div>
            </div>
        </div>
        <footer class="card-footer mb-0 pb-0">
            <div class="card-footer-item">
                <button type="button" class="button is-small is-fullwidth"
                    wire:click="$emitTo('hltb-modal', 'activateModal', '{{ $nomination->game_name }}')"
                    onclick="buttonLoad(this);">Get HLTB times</button>
            </div>
            <div class="card-footer-item">
                @php
                    $isRanked = in_array(
                        $nomination->id,
                        array_slice($currentOrder, 0, array_search($divider, $currentOrder)),
                    );
                @endphp
                <button type="button" class="button is-small is-fullwidth"
                    wire:click="moveNomination('{{ $nomination->id }}', '{{ $short }}')">
                    <span class="icon is-small">
                        <i class="fas fa-arrow-{{ $isRanked ? 'down' : 'up' }}"></i>
                    </span>
                    <span>{{ $isRanked ? 'Unrank' : 'Rank' }}</span>
                </button>
            </div>
        </footer>
    </div>
</x-laravel-blade-sortable::sortable-item>
