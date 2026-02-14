<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <style>[x-cloak] { display: none !important; }</style>
    @filamentStyles
    @vite('resources/css/app.css')
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Expense Tracker</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your expenses with ease</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                {{ $slot }}
            </div>
        </div>
    </div>

    @livewire('notifications')
    @filamentScripts
    @vite('resources/js/app.js')
</body>
</html>
