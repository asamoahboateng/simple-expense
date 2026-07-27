<div>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Pull data from old server</h2>
    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
        Enter the URL of the old server. This will pull its users, categories, and expenses into this server. Safe to run more than once.
    </p>

    <form wire:submit="pullFromOldServer" class="space-y-5">
        <div>
            <label for="old_server_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Old server URL</label>
            <input wire:model="old_server_url" type="url" id="old_server_url" placeholder="https://old.example.com"
                   class="w-full p-2 font-normal rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('old_server_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="w-full flex justify-center py-2.5 px-4 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-75">
            <span wire:loading.remove wire:target="pullFromOldServer">Pull data</span>
            <span wire:loading wire:target="pullFromOldServer">Pulling data...</span>
        </button>
    </form>
</div>
