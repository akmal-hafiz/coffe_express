<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-coffee-900 antialiased bg-cream-50 selection:bg-coffee-200 selection:text-coffee-900">
        <!-- Ambient Background Blobs -->
        <div class="blob-container">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            <div class="blob blob-3"></div>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10">
            <div class="mb-6">
                <a href="/" class="transition-transform duration-300 hover:scale-110 inline-block">
                    <x-application-logo class="w-24 h-24 fill-current text-coffee-700 drop-shadow-lg" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 glass-panel overflow-hidden sm:rounded-2xl relative">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
