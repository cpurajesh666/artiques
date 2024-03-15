<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Artiques') }}</title>
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
    @livewireStyles
</head>
<body class="font-sans antialiased">
@include('partials._nav')
<main class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
    {{ $slot }}
</main>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@livewireScripts
</body>