<!DOCTYPE html>
<html lang="en">
<head>
    <title>PG GOTM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    @livewireStyles
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bulmaswatch/darkly/bulmaswatch.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
</head>
<body>
<section class="section">
    <div class="container">
        <div class="tabs is-centered is-boxed is-medium">
            <ul>
                <li class="{{ (Route::currentRouteName() == 'main') ? 'is-active' : '' }}">
                    <a href="{{ url('/') }}">
                        <span class="icon is-small"><i class="fas fa-gamepad" aria-hidden="true"></i></span>
                        <span>Main</span>
                    </a>
                </li>
                <li class="{{ (Route::currentRouteName() == 'nominate') ? 'is-active' : '' }}">
                    <a href="{{ url('/nominate') }}">
                        <span class="icon is-small"><i class="fas fa-vote-yea" aria-hidden="true"></i></span>
                        <span>Nominate / Vote</span>
                    </a>
                </li>
                <li class="{{ (Route::currentRouteName() == 'jury') ? 'is-active' : '' }}">
                    <a>
                        <span class="icon is-small"><i class="fas fa-users-cog" aria-hidden="true"></i></span>
                        <span>Jury</span>
                    </a>
                </li>
            </ul>
        </div>

        {{ $slot }}

    </div>
</section>
@livewireScripts
</body>
</html>
