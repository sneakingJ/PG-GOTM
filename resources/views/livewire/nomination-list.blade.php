<div class="box">
    @foreach($nominations as $nomination)
        <div class="level mb-0 mr-4 is-clickable is-hoverable">
            <div class="level-left p-4">
                <div class="level-item level-item-cover">
                    <img src="{{ $nomination->game_cover }}" class="igdb-cover">
                </div>
                <div class="level-item">
                    <strong>{{ $nomination->game_name }}</strong>
                </div>
            </div>
        </div>
    @endforeach
</div>
