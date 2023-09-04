<!DOCTYPE html>
<html class="h-full"  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Wowsbar') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="icon" type="image/png" sizes="16x16" href="{{ url('favicons/wowsbar-website-favicon-color-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ url('favicons/wowsbar-website-favicon-color-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="48x48" href="{{ url('favicons/wowsbar-website-favicon-color-180x180.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ url('favicons/wowsbar-website-favicon-color-180x180.png') }}">

        <!-- Scripts -->
        @routes('organisation')
        {{Vite::useHotFile('organisation.hot')->useBuildDirectory('organisation')->withEntryPoints(['resources/js/app-organisation.js'])}}
        @inertiaHead
    </head>
    <body class="font-sans antialiased h-full">
        @inertia
    </body>
</html>
