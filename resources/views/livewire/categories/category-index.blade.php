<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Categories</h2>
        <div>
            {{ $this->createMainCategoryAction }}
        </div>
    </div>

    @if($mainCategories->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No categories yet</h3>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Get started by creating your first expense category.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($mainCategories as $category)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden" x-data="{ expanded: true }">
                    {{-- Main Category Header --}}
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <button @click="expanded = !expanded" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-5 h-5 transition-transform" :class="expanded ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                            @if($category->color)
                                <div class="w-4 h-4 rounded-full" style="background-color: {{ $category->color }}"></div>
                            @endif
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $category->name }}</h3>
                            @if($category->description)
                                <span class="text-sm text-gray-500 dark:text-gray-400">&mdash; {{ $category->description }}</span>
                            @endif
                            <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2 py-0.5 rounded-full">
                                {{ $category->subCategories->count() }} subs
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            {{ $this->createSubCategoryAction(['main_category_id' => $category->id]) }}
                            {{ $this->editMainCategoryAction(['id' => $category->id]) }}
                            {{ $this->deleteMainCategoryAction(['id' => $category->id]) }}
                        </div>
                    </div>

                    {{-- Subcategory Tree --}}
                    <div x-show="expanded" x-collapse class="px-6 py-3">
                        @if($category->rootSubCategories->isEmpty())
                            <p class="text-sm text-gray-400 dark:text-gray-500 py-2">No subcategories yet.</p>
                        @else
                            @include('livewire.categories.partials.subcategory-tree', [
                                'subCategories' => $category->rootSubCategories,
                                'depth' => 0,
                            ])
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <x-filament-actions::modals />
</div>
