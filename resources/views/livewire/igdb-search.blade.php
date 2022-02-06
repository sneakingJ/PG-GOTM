<div class="box igdb-box">
    <div class="igdb-search">
        <div class="columns">
            <div class="column is-four-fifths">
                <p class="control has-icons-left">
                    <input wire:model.debounce.400ms="searchName" type="text" class="input" placeholder="Search for game">
                    <span class="icon is-left">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </span>
                </p>
            </div>
            <div class="column is-one-fifth">
                <p class="control has-icons-left">
                    <input wire:model="searchYear" type="text" class="input" placeholder="Search year">
                    <span class="icon is-left">
                        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div wire:loading.block wire:target="searchName, searchYear" class="mt-4">
        <progress class="progress is-small is-info" max="100">50%</progress>
    </div>

    @if($noResults)
        <div class="mt-4 ml-4" wire:loading.remove>
            <strong>No results found</strong>
        </div>
    @endif

    <div class="game-list game-list-search" wire:loading.remove>
        @foreach($games as $game)
            <div class="level mb-0 mr-4 is-clickable is-hoverable"
                 wire:click="$emitTo('nominate-modal', 'activateModal', '{{ $game['id'] }}', '{{ $game['name'] }}', '{{ $game['cover'] }}')">
                <div class="level-left p-4">
                    <div class="level-item igdb-cover">
                        <img src="{{ $game['cover'] }}">
                    </div>
                    <div class="level-item">
                        <strong class="igdb-name">{{ $game['name'] }} ({{ $game['year'] }})</strong>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <livewire:nominate-message/>

    <livewire:nominate-modal/>
</div>
