<div class="block">
    <div class="box">
        @foreach($games as $game)
            {{ $game->game_name }}
        @endforeach
    </div>
</div>
