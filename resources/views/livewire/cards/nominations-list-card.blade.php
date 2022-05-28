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
                    @php
                        $firstPitch = $nomination->pitches()->orderBy('created_at')->first()
                    @endphp
                    <span class="pitch" wire:click="$emitTo('pitches-modal', 'activateModal', '{{ $nomination->id }}')">
                        @if(!empty($firstPitch))
                            {{ \Illuminate\Support\Str::limit($firstPitch->pitch, 50, '... ') }}
                            <i class="fas fa-pen"></i>
                        @else
                            <i class="fas fa-pen"></i>
                        @endif
                    </span><br><br>
                    <a href="{{ $nomination->game_url }}" target="_blank" class="is-size-6"><span class="icon is-small"><i class="fas fa-external-link-alt" aria-hidden="true"></i></span> <span>IGDB</span></a>
                    <button type="button" class="button is-pulled-right" style="margin-top:-15px;" wire:click="$emitTo('hltb-modal', 'activateModal', '{{ $nomination->game_name }}')" onclick="buttonLoad(this);">Get HLTB times</button>
                </p>
            </div>
        </div>
    </div>
</x-laravel-blade-sortable::sortable-item>
