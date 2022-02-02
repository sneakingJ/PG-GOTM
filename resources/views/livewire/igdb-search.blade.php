<div>
    Search for game: <input wire:model="searchstring" type="text"> <br><br>

    @foreach($games as $game)
        <div>
            <img src="{{ $game['cover'] }}" width="90px" height="90px">

            {{ $game['name'] }} <br><br><br>
        </div>
    @endforeach
</div>
