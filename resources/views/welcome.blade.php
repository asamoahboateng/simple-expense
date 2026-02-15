<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Expense Tracker</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100">

    {{-- Navigation --}}
    <nav class="fixed top-0 w-full bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 z-50">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <span class="text-lg font-bold">Expense Tracker</span>
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        Get Started
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="pt-32 pb-20 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-medium mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                Simple &amp; Powerful
            </div>
            <h1 class="text-5xl sm:text-6xl font-bold tracking-tight mb-6">
                Track your expenses
                <span class="text-blue-600">effortlessly</span>
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-10">
                Organize spending by category, visualize trends with charts, and export reports to Excel or PDF. All in one clean, simple tool.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="w-full sm:w-auto px-8 py-3.5 text-base font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-600/25 transition-all hover:shadow-xl hover:shadow-blue-600/30">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}"
                       class="w-full sm:w-auto px-8 py-3.5 text-base font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-600/25 transition-all hover:shadow-xl hover:shadow-blue-600/30">
                        Start Tracking Free
                    </a>
                    <a href="{{ route('login') }}"
                       class="w-full sm:w-auto px-8 py-3.5 text-base font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition-colors">
                        Sign In
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-20 px-6 bg-white dark:bg-gray-900">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold mb-4">Everything you need to manage expenses</h2>
                <p class="text-gray-600 dark:text-gray-400 max-w-xl mx-auto">Built with simplicity in mind. No clutter, no complexity. Just the tools you need.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div class="p-6 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/40 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Smart Categories</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                        Organize expenses with main categories and unlimited subcategory nesting. Create a hierarchy that fits your needs.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="p-6 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/40 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Quick Expense Entry</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                        Record expenses in seconds. Add a title, amount, category, and who made the purchase. Filter, search, and sort with ease.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="p-6 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/40 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Visual Dashboard</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                        See your spending at a glance with summary cards, category pie charts, and monthly trend graphs. Filter by any date range.
                    </p>
                </div>

                {{-- Feature 4 --}}
                <div class="p-6 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/40 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Export Reports</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                        Generate reports filtered by date, category, or person. Export to Excel for spreadsheets or PDF for sharing and archiving.
                    </p>
                </div>

                {{-- Feature 5 --}}
                <div class="p-6 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/40 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Multi-Person Tracking</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                        Track who made each expense. Your name is filled in automatically, but you can log purchases made by anyone on your team.
                    </p>
                </div>

                {{-- Feature 6 --}}
                <div class="p-6 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 bg-teal-100 dark:bg-teal-900/40 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Secure &amp; Private</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                        Your data stays yours. Each user only sees their own expenses. Secure authentication keeps everything protected.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 px-6">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to take control of your spending?</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-8">Create a free account and start tracking your expenses in minutes.</p>
            @auth
                <a href="{{ route('dashboard') }}"
                   class="inline-flex px-8 py-3.5 text-base font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-600/25 transition-all hover:shadow-xl hover:shadow-blue-600/30">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}"
                   class="inline-flex px-8 py-3.5 text-base font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-600/25 transition-all hover:shadow-xl hover:shadow-blue-600/30">
                    Get Started Now
                </a>
            @endauth
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-200 dark:border-gray-800 py-8 px-6">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500 dark:text-gray-400">
            <span>Expense Tracker &copy; {{ date('Y') }}</span>
            <span>Built with Laravel, Livewire &amp; Filament</span>
        </div>
    </footer>

</body>
</html>
