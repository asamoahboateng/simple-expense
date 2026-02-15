<div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Create your account</h2>

    <form wire:submit="register" class="space-y-5">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
            <input wire:model="name" type="text" id="name" autocomplete="name"
                   class="w-full p-2 font-normal rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input wire:model="email" type="email" id="email" autocomplete="email"
                   class="w-full p-2 font-normal rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
            <input wire:model="password" type="password" id="password" autocomplete="new-password"
                   class="w-full p-2 font-normal rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
            <input wire:model="password_confirmation" type="password" id="password_confirmation" autocomplete="new-password"
                   class="w-full p-2 font-normal rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <button type="submit"
                class="w-full flex justify-center py-2.5 px-4 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-75">
            <span wire:loading.remove wire:target="register">Create account</span>
            <span wire:loading wire:target="register">Creating account...</span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
        Already have an account?
        <a href="{{ route('login') }}" wire:navigate class="text-blue-600 hover:text-blue-500 font-medium">Sign in</a>
    </p>
</div>
