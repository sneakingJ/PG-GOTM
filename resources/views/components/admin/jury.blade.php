<div>
    <div class="block">
        <button class="button" type="button" wire:click="juryToVoting">Close Jury and start Voting</button>
    </div>
    <div class="block">
        <h5>Short Games</h5>
        <ul>
            @foreach($shortGames as $shortGame)
                <li>{{ $shortGame->game_name }}</li>
            @endforeach
        </ul>
        <ul>
            @foreach($shortGames as $shortGame)
                <li>
                @foreach($shortGame->pitches as $pitch)
                    {{ $pitch->pitch }}<br>
                @endforeach
                </li>
            @endforeach
        </ul>
    </div>
    <div class="block">
        <h5>Long Games</h5>
        <ul>
            @foreach($longGames as $longGame)
                <li>{{ $longGame->game_name }}</li>
            @endforeach
        </ul>
        <ul>
            @foreach($longGames as $longGame)
                <li>
                    @foreach($longGame->pitches as $pitch)
                        {{ $pitch->pitch }}<br>
                    @endforeach
                </li>
            @endforeach
        </ul>
    </div>
</div>
