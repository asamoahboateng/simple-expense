<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <style>[x-cloak] { display: none !important; }</style>
    @vite('resources/css/app.css')
    @filamentStyles
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex flex-col">
        <header class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gray-700 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v2H4V6zm0 4h16v8a2 2 0 01-2 2H6a2 2 0 01-2-2v-8z"/>
                    </svg>
                </div>
                <span class="text-lg font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
            </div>

            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 flex items-center justify-center p-6">
            {{ $slot }}
        </main>
    </div>

    @livewire('notifications')
    @filamentScripts
    @vite('resources/js/app.js')
</body>
</html>
