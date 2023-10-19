<!DOCTYPE html>
<html class="h-full"  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Wowsbar') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <link rel="icon" type="image/png" sizes="16x16" href="{{ url('favicons/wowsbar-website-favicon-color-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ url('favicons/wowsbar-website-favicon-color-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="48x48" href="{{ url('favicons/wowsbar-website-favicon-color-180x180.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ url('favicons/wowsbar-website-favicon-color-180x180.png') }}">

        <!-- Scripts -->
        @routes('public')
        {{Vite::useHotFile('public.hot')->useBuildDirectory('public')->withEntryPoints(['resources/js/app-public.js'])}}
        @inertiaHead
    </head>
    <body class="font-sans antialiased h-full">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        @inertia
    </body>
</html>
