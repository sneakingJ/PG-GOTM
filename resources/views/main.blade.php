<html>
<head>
    <title>PG GOTM</title>
</head>
<body>
    Hello, {{ $authId }}
    <br><br>
    @if (is_null($authId))
        <a href="{{ url('/discord-auth/login') }}">Login</a>
    @else
        <a href="{{ url('/discord-auth/logout') }}">Logout</a>
    @endif
</body>
</html>
