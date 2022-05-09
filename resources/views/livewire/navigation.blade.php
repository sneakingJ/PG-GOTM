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
                @elseif($juryExists)
                    Jury at work
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
            <span>Jury Members</span>
        </a>
    </li>
    <li class="{{ (Route::currentRouteName() == 'privacy') ? 'is-active' : '' }}">
        <a href="{{ route('privacy') }}">
            <span class="icon is-small"><i class="fas fa-lock" aria-hidden="true"></i></span>
            <span>Privacy</span>
        </a>
    </li>
</ul>
