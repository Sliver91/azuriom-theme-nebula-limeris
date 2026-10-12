{{-- Nebula Limeris - Limeris.fr by Sliver91 --}}
@php
    $nlDefaultDark = (bool) theme_config('default_dark', true);
    $nlDarkColor = theme_config('color_dark', '#7af0c4');
    $nlLightColor = theme_config('color_light', '#0c7458');
    $nlIsDark = dark_theme($nlDefaultDark);
    $nlSky = theme_config('background', 'bleu');
    $nlSky = in_array($nlSky, ['violet', 'bleu', 'emeraude', 'braise', 'aube', 'etoiles'], true) ? $nlSky : null;
    $nlPick = function (string $key, array $allowed, string $default) {
        $value = theme_config($key, $default);

        return in_array($value, $allowed, true) ? $value : $default;
    };
    $nlVeil = $nlPick('veil', ['leger', 'moyen', 'fort'], 'moyen');
    $nlRadius = $nlPick('radius', ['droit', 'leger', 'rond'], 'leger');
    $nlFont = $nlPick('title_font', ['condense', 'large', 'technique'], 'condense');
@endphp
<!DOCTYPE html>
@include('elements.base')
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', setting('description', ''))">
    <meta name="theme-color" content="{{ $nlIsDark ? '#2a1247' : '#ebe4f8' }}">
    <meta name="author" content="Azuriom">

    <meta property="og:title" content="@yield('title')">
    <meta property="og:type" content="@yield('type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ favicon() }}">
    <meta property="og:description" content="@yield('description', setting('description', ''))">
    <meta property="og:site_name" content="{{ site_name() }}">
    @stack('meta')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ site_name() }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ favicon() }}">

    <!-- Scripts -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('vendor/axios/axios.min.js') }}" defer></script>
    <script src="{{ asset('js/script.js') }}" defer></script>
    <script src="{{ theme_asset('js/theme.js') }}" defer></script>

    <!-- Page level scripts -->
    @stack('scripts')

    <!-- Fonts -->
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ theme_asset('css/fonts.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/base.css') }}" rel="stylesheet">
    <link href="{{ theme_asset('css/style.css') }}" rel="stylesheet">
    @stack('styles')
    @include('elements.nl-colors', ['dark' => $nlDarkColor, 'light' => $nlLightColor])
</head>

<body class="nl d-flex flex-column nl-veil-{{ $nlVeil }} nl-radius-{{ $nlRadius }} nl-font-{{ $nlFont }} @if($nlSky) nl-sky nl-sky-{{ $nlSky }} @endif" data-bs-theme="{{ $nlIsDark ? 'dark' : 'light' }}">
<div id="app" class="flex-shrink-0">
    <header class="nl-header @if(theme_config('nav_sticky', false)) nl-header-sticky @endif">
        @include('elements.navbar')
    </header>

    @yield('app')
</div>

<footer class="nl-footer mt-auto">
    @include('elements.footer')
</footer>

@stack('footer-scripts')

</body>
</html>
