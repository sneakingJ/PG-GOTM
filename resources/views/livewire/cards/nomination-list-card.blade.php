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
                <p class="subtitle is-size-5 is-relative">
                    @php
                        $firstPitch = $nomination->pitches()->first()
                    @endphp
                    @if(!empty($firstPitch))
                        <span class="pitch" wire:click="$emitTo('nomination-list', 'activateModal', '{{ $nomination->id }}')">
                            {{ \Illuminate\Support\Str::limit($firstPitch->pitch, 50, '... ') }}
                            @if (\Illuminate\Support\Str::length($firstPitch->pitch) > 50)
                                <i class="fas fa-sort-down"></i>
                            @endif
                        </span><br><br>
                    @else
                        <span></span><br><br>
                    @endif
                    <a href="{{ $nomination->game_url }}" target="_blank" class="is-size-6"><span class="icon is-small"><i class="fas fa-external-link-alt" aria-hidden="true"></i></span> <span>IGDB</span></a>
                </p>
            </div>
        </div>
    </div>
</div>
