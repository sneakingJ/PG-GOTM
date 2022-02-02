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
        <div class="block">
            Hello,

            @if (is_null($user))
                <br><br>
                <a href="{{ url('/discord-auth/login') }}">Auth</a>
            @else
                {{ $user->name }}
                <br><br>
                <a href="{{ url('/discord-auth/logout') }}">De-Auth</a>
            @endif
        </div>
        <div class="block">
            <livewire:igdb-search/>
        </div>
    </div>
</section>

@livewireScripts
</body>
</html>
