<div class="box igdb-box">
    <livewire:nominate-message/>

    <div class="igdb-search">
        <div class="columns">
            <div class="column is-four-fifths">
                <p class="control has-icons-left">
                    <input wire:model.debounce.400ms="searchName" type="text" class="input" placeholder="Search for game" autofocus>
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
            <strong>No results found. Maybe you have a typo? If not, please check <a href="https://www.igdb.com/" target="_blank">IGDB</a> manually and insert the complete title of the game page.</strong>
        </div>
    @endif

    <div class="game-list-search" wire:loading.remove>
        @foreach($games as $game)
            <div class="card mb-4 is-hoverable @if(!$game['alreadyNominated'] && !$game['alreadyWon'])is-clickable @endif"
                 @if(!$game['alreadyNominated'] && !$game['alreadyWon'])wire:click="$emitTo('nominate-modal', 'activateModal', '{{ $game['id'] }}', '{{ htmlentities($game['name']) }}', '{{ $game['cover'] }}')" @endif>
                <div class="card-content">
                    <div class="media">
                        <div class="media-left">
                            <figure class="image mt-4 igdb-cover">
                                <img src="{{ $game['cover']  }}">
                            </figure>
                        </div>
                        <div class="media-content">
                            <p class="title is-4 mb-0">{{ $game['name'] }} ({{ $game['year'] }})
                                @if($game['alreadyNominated'])- <span class="invalid">Already nominated</span>@endif
                                @if($game['alreadyWon'])- <span class="invalid">Already was GOTM</span>@endif
                            </p>
                            <div class="platformlist is-clearfix">
                                @foreach($game['platforms'] as $platform)
                                    <div class="platform-image">
                                        <span class="helper"></span><img src="{{ $platform->logo }}" title="{{ $platform->name }}">
                                    </div>
                                @endforeach
                            </div>
                            <p class="subtitle is-size-5">
                                <a href="{{ $game['url'] }}" target="_blank" class="is-size-6" wire:click.stop=""><span class="icon is-small"><i class="fas fa-external-link-alt" aria-hidden="true"></i></span> <span>IGDB</span></a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <livewire:nominate-modal/>
    <livewire:hltb-modal/>
</div>
