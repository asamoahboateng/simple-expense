<div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Sign in to your account</h2>

    <form wire:submit="login" class="space-y-5">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input wire:model="email" type="email" id="email" autocomplete="email"
                   class="w-full p-2 font-normal rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
            <input wire:model="password" type="password" id="password" autocomplete="current-password"
                   class="w-full p-2 font-normal rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2">
                <input wire:model="remember" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                <span class="text-sm text-gray-600 dark:text-gray-400">Remember me</span>
            </label>
        </div>

        <button type="submit"
                class="w-full flex justify-center py-2.5 px-4 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-75">
            <span wire:loading.remove wire:target="login">Sign in</span>
            <span wire:loading wire:target="login">Signing in...</span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
        Don't have an account?
        <a href="{{ route('register') }}" wire:navigate class="text-blue-600 hover:text-blue-500 font-medium">Register</a>
    </p>
</div>
