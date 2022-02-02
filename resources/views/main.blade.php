<html lang="en">
<head>
    <title>PG GOTM</title>
    @livewireStyles
</head>
<body>
    Hello,

    @if (is_null($user))
        <br><br>
        <a href="{{ url('/discord-auth/login') }}">Auth</a>
    @else
        {{ $user->name }}
        <br><br>
        <a href="{{ url('/discord-auth/logout') }}">De-Auth</a>
    @endif
    <br><br>
    <livewire:igdb-search />

    @livewireScripts
</body>
</html>
