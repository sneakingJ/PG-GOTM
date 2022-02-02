<div class="box igdb-box">
    <div class="igdb-search">
        <p class="control has-icons-left">
            <input wire:model.debounce.400ms="searchstring" type="text" class="input" placeholder="Search for game">
            <span class="icon is-left">
                <i class="fas fa-search" aria-hidden="true"></i>
            </span>
        </p>
    </div>

    <div wire:loading.block wire:target="searchstring" class="mt-4">
        <progress class="progress is-small is-info" max="100">50%</progress>
    </div>

    @if($noResults)
        <div class="mt-4 ml-4" wire:loading.remove>
            <strong>No results found</strong>
        </div>
    @endif

    <div class="igdb-results">
        @foreach($games as $game)
            <div class="level mb-0 mr-4 is-clickable is-hoverable" wire:loading.remove>
                <div class="level-left p-4">
                    <div class="level-item">
                        <img src="{{ $game['cover'] }}" class="igdb-cover">
                    </div>
                    <div class="level-item">
                        <strong>{{ $game['name'] }}</strong>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
