<ul>
    <li class="{{ (Route::currentRouteName() == 'main') ? 'is-active' : '' }}">
        <a href="{{ route('main') }}">
            <span class="icon is-small"><i class="fas fa-gamepad" aria-hidden="true"></i></span>
            <span>Main</span>
        </a>
    </li>
    <li class="{{ (Route::currentRouteName() == 'nominate' || Route::currentRouteName() == 'voting') ? 'is-active' : '' }}">
        <a href="@if($nominationExists){{ route('nominate') }}@elseif($votingExists){{ route('voting') }}@else#@endif">
            <span class="icon is-small"><i class="fas fa-vote-yea" aria-hidden="true"></i></span>
            <span>
                @if($nominationExists)
                    Nominate
                @elseif($votingExists)
                    Vote
                @else
                    Play the games!
                @endif
            </span>
        </a>
    </li>
    <li class="{{ (Route::currentRouteName() == 'jury') ? 'is-active' : '' }}">
        <a href="{{ route('jury') }}">
            <span class="icon is-small"><i class="fas fa-users-cog" aria-hidden="true"></i></span>
            <span>Jury</span>
        </a>
    </li>
</ul>
