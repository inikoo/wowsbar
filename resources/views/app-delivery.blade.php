<!DOCTYPE html>
<html class="h-full"  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @if(Config::get('app.analytics.delivery'))
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ Config::get('app.analytics.delivery') }}"></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());
              gtag('config', '{{ Config::get('app.analytics.delivery') }}');
            </script>
        @endif
        <title inertia>{{ config('app.name', 'Wowsbar') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @routes('delivery')
        {{Vite::useHotFile('delivery.hot')->useBuildDirectory('delivery')->withEntryPoints(['resources/js/app-delivery.js'])}}
        @inertiaHead
    </head>
    <body class="font-sans antialiased h-full">
        @inertia
    </body>
</html>
