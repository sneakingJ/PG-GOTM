<x-laravel-blade-sortable::sortable-item sort-key="{{ $nomination->id }}" class="card mb-4">
    <div class="card-content">
        <div class="media">
            <div class="media-left">
                <figure class="image igdb-cover">
                    <img src="{{ $nomination->game_cover }}">
                </figure>
                <div class="mt-4">
                    <a href="{{ $nomination->game_url }}" target="_blank" class="is-size-6"><span class="icon is-small"><i class="fas fa-external-link-alt" aria-hidden="true"></i></span> <span>IGDB</span></a>
                </div>

            </div>
            <div class="media-content">
                <p class="title is-4">{{ $nomination->game_name }}</p>
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
                    </span><br><br>
                </p>
                <div class="buttons-container">
                    <button type="button" class="button"
                        wire:click="$emitTo('hltb-modal', 'activateModal', '{{ $nomination->game_name }}')"
                        onclick="buttonLoad(this);">Get HLTB times</button>
                    @php
                        $isRanked = in_array(
                            $nomination->id,
                            array_slice($currentOrder, 0, array_search($divider, $currentOrder)),
                        );
                    @endphp
                    <button type="button" class="button"
                        wire:click="moveNomination('{{ $nomination->id }}', '{{ $short }}')">
                        <i class="fas fa-arrow-{{ $isRanked ? 'down' : 'up' }}"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-laravel-blade-sortable::sortable-item>
