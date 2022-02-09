<ul>
    <li class="{{ (Route::currentRouteName() == 'main') ? 'is-active' : '' }}">
        <a href="{{ url('/') }}">
            <span class="icon is-small"><i class="fas fa-gamepad" aria-hidden="true"></i></span>
            <span>Main</span>
        </a>
    </li>
    <li class="{{ (Route::currentRouteName() == 'nominate') ? 'is-active' : '' }}">
        <a href="@if(!$nominationExists && !$votingExists) # @else {{ url('/nominate') }} @endif">
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
        <a>
            <span class="icon is-small"><i class="fas fa-users-cog" aria-hidden="true"></i></span>
            <span>Jury</span>
        </a>
    </li>
</ul>
