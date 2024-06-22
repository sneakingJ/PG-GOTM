<div class="voting">
    <div class="columns is-centered">
        <div class="column is-narrow has-text-centered">
            <h1 class="title is-2">Drag and Drop the games</h1>
            <h1 class="title is-4">to sort them in the priority you want them to win</h1>
            <p></p>
            <p>Please only vote for games you actually want to play next month :)</p>
        </div>
    </div>

    <div class="box">
        <div class="columns is-multiline">
            <div class="column is-full-mobile is-half-tablet">
                <div class="box mb-0 pb-0">
                    <div class="columns is-vcentered is-mobile">

                        <div class="column">
                            <h2 class="title is-3">Long Games</h2>
                        </div>
                        <div class="column is-narrow">
                            @livewire('vote-status', ['short' => false])
                        </div>
                    </div>
                    <div class="buttons hidden-button-wrapper">
                        @if ($votedLong)
                            <button class="button" type="button"
                                wire:click="$emitTo('voting-list', 'deleteVote', false)">Unvote Long</button>
                        @endif
                    </div>
                </div>
                @php
                    $currentOrderLong = is_array($currentOrder[0]) ? $currentOrder[0] : $currentOrder[0]->toArray();
                    $dividerIndex = array_search($divider, $currentOrderLong);
                    $rankedGames = array_slice($currentOrderLong, 0, $dividerIndex);
                    $unrankedGames = array_slice($currentOrderLong, $dividerIndex + 1);
                @endphp
                <x-laravel-blade-sortable::sortable group="long" name="long" :allow-drop="false"
                    wire:onSortOrderChange="sortChange">
                    @if (empty($rankedGames))
                        <div class="card mb-4">
                            <div class="card-content">
                                <div class="content has-text-centered">
                                    Drag games here to rank them
                                </div>
                            </div>
                        </div>
                    @else
                        @foreach ($rankedGames as $item)
                            <x-laravel-blade-sortable::sortable-item sort-key="{{ $item }}">
                                @include('livewire.cards.votings-list-card', [
                                    'nomination' => \App\Models\Nomination::find($item),
                                    'currentOrder' => $currentOrderLong,
                                    'divider' => $divider,
                                    'short' => 0,
                                ])
                            </x-laravel-blade-sortable::sortable-item>
                        @endforeach
                    @endif

                    <x-laravel-blade-sortable::sortable-item sort-key="{{ $divider }}">
                        @include('livewire.cards.divider-card')
                    </x-laravel-blade-sortable::sortable-item>

                    @if (empty($unrankedGames))
                        <div class="card mb-4">
                            <div class="card-content">
                                <div class="content has-text-centered">
                                    Drag games here to unrank them
                                </div>
                            </div>
                        </div>
                    @else
                        @foreach ($unrankedGames as $item)
                            <x-laravel-blade-sortable::sortable-item sort-key="{{ $item }}">
                                @include('livewire.cards.votings-list-card', [
                                    'nomination' => \App\Models\Nomination::find($item),
                                    'currentOrder' => $currentOrderLong,
                                    'divider' => $divider,
                                    'short' => 0,
                                ])
                            </x-laravel-blade-sortable::sortable-item>
                        @endforeach
                    @endif
                </x-laravel-blade-sortable::sortable>
            </div>
            <div class="column is-full-mobile is-half-tablet">
                <div class="box mb-0 pb-0">
                    <div class="columns is-vcentered is-mobile">

                        <div class="column">
                            <h2 class="title is-3">Short Games</h2>
                        </div>
                        <div class="column is-narrow">
                            @livewire('vote-status', ['short' => true])
                        </div>
                    </div>
                    <div class="buttons hidden-button-wrapper">
                        @if ($votedShort)
                            <button class="button" type="button"
                                wire:click="$emitTo('voting-list', 'deleteVote', true)">Unvote Short</button>
                        @endif
                    </div>
                </div>
                @php
                    $currentOrderShort = is_array($currentOrder[1]) ? $currentOrder[1] : $currentOrder[1]->toArray();
                    $dividerIndex = array_search($divider, $currentOrderShort);
                    $rankedGames = array_slice($currentOrderShort, 0, $dividerIndex);
                    $unrankedGames = array_slice($currentOrderShort, $dividerIndex + 1);
                @endphp
                <x-laravel-blade-sortable::sortable group="short" name="short" :allow-drop="false"
                    wire:onSortOrderChange="sortChange">
                    @if (empty($rankedGames))
                        <div class="card mb-4">
                            <div class="card-content">
                                <div class="content has-text-centered">
                                    Drag games here to rank them
                                </div>
                            </div>
                        </div>
                    @else
                        @foreach ($rankedGames as $item)
                            <x-laravel-blade-sortable::sortable-item sort-key="{{ $item }}">
                                @include('livewire.cards.votings-list-card', [
                                    'nomination' => \App\Models\Nomination::find($item),
                                    'currentOrder' => $currentOrderShort,
                                    'divider' => $divider,
                                    'short' => 1,
                                ])
                            </x-laravel-blade-sortable::sortable-item>
                        @endforeach
                    @endif

                    <x-laravel-blade-sortable::sortable-item sort-key="{{ $divider }}">
                        @include('livewire.cards.divider-card')
                    </x-laravel-blade-sortable::sortable-item>

                    @if (empty($unrankedGames))
                        <div class="card mb-4">
                            <div class="card-content">
                                <div class="content has-text-centered">
                                    Drag games here to unrank them
                                </div>
                            </div>
                        </div>
                    @else
                        @foreach ($unrankedGames as $item)
                            <x-laravel-blade-sortable::sortable-item sort-key="{{ $item }}">
                                @include('livewire.cards.votings-list-card', [
                                    'nomination' => \App\Models\Nomination::find($item),
                                    'currentOrder' => $currentOrderShort,
                                    'divider' => $divider,
                                    'short' => 1,
                                ])
                            </x-laravel-blade-sortable::sortable-item>
                        @endforeach
                    @endif
                </x-laravel-blade-sortable::sortable>
            </div>
        </div>
    </div>

    @livewire('pitches-modal', ['editPossible' => false])
    <livewire:hltb-modal />
</div>

<style>
    .hidden-button-wrapper {
        margin-bottom: 0px;
        min-height: 60px
    }

    .hidden-button-wrapper button {
        margin-bottom: 0px;
    }
</style>
