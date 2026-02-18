<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Categories</h2>
        <div>
            {{ $this->createMainCategoryAction }}
        </div>
    </div>

    {{-- Search & Filters --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search categories..."
                class="w-full pl-10 pr-4 py-2.5 p-2 font-normal bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
            @if($search)
                <button wire:click="$set('search', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            @endif
        </div>
        <div class="flex items-center gap-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-1">
            @foreach(['all' => 'All', 'with-subs' => 'With Subs', 'empty' => 'Empty'] as $value => $label)
                <button
                    wire:click="$set('filter', '{{ $value }}')"
                    class="px-3 py-1.5 text-sm rounded-lg transition-colors {{ $filter === $value ? 'bg-blue-500 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Content --}}
    @if($mainCategories->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-12 text-center">
            @if($search || $filter !== 'all')
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No matching categories</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Try adjusting your search or filters.</p>
            @else
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No categories yet</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Get started by creating your first expense category.</p>
            @endif
        </div>
    @else
        {{-- Masonry Card Grid --}}
        <div class="columns-1 md:columns-2 xl:columns-3 gap-4 space-y-4">
            @foreach($mainCategories as $category)
                <div class="break-inside-avoid" x-data="{ expanded: true }">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                        {{-- Card Header --}}
                        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
                            <div class="flex items-center justify-between gap-2">
                                {{-- Title --}}
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <button @click="expanded = !expanded" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 flex-shrink-0 p-0.5">
                                        <svg class="w-4 h-4 transition-transform duration-200" :class="expanded && 'rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                    @if($category->color)
                                        <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $category->color }}"></span>
                                    @endif
                                    <a href="{{ route('categories.report', $category) }}" wire:navigate class="font-semibold text-gray-900 dark:text-white truncate hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                        {{ $category->name }}
                                    </a>
                                    <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-2 py-0.5 rounded-full flex-shrink-0">
                                        {{ $category->rootSubCategoriesWithChildren->count() }}
                                    </span>
                                </div>

                                {{-- Icon actions --}}
                                <div class="flex items-center gap-0.5 flex-shrink-0" @click.stop>
                                    <button wire:click="startCreateSubCategory({{ $category->id }})" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Add Subcategory">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                    <button wire:click="startEditMainCategory({{ $category->id }})" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </button>
                                    <button wire:click="startDeleteMainCategory({{ $category->id }})" class="p-1.5 text-gray-400 hover:text-red-500 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>

                            @if($category->description)
                                <p class="mt-1.5 ml-7 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">{{ $category->description }}</p>
                            @endif
                        </div>

                        {{-- Card Body: Subcategory Tree --}}
                        <div x-show="expanded" x-collapse>
                            @if($category->rootSubCategoriesWithChildren->isEmpty())
                                <div class="px-5 py-4 text-sm text-gray-400 dark:text-gray-500 italic">
                                    No subcategories yet.
                                </div>
                            @else
                                <div class="px-4 py-3">
                                    @include('livewire.categories.partials.subcategory-tree', [
                                        'subCategories' => $category->rootSubCategoriesWithChildren,
                                        'depth' => 0,
                                    ])
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <x-filament-actions::modals />
</div>
