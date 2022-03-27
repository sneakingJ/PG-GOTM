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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script defer src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>
    <x-laravel-blade-sortable::scripts/>
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
</head>
<body>
<section class="section">
    <div class="container">
        <div class="tabs is-centered is-boxed is-medium">
            @livewire('navigation')
        </div>

        {{ $slot }}

    </div>
</section>
@livewireScripts
</body>
</html>
