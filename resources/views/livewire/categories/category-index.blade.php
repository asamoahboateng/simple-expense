<div>
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Categories</h2>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                {{ $mainCategories->total() }} {{ Str::plural('category', $mainCategories->total()) }}
                &middot;
                {{ $mainCategories->total() > 0 ? $mainCategories->firstItem() . '–' . $mainCategories->lastItem() . ' shown' : 'none' }}
            </p>
        </div>
        <div>
            {{ $this->createMainCategoryAction }}
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-5">
        {{-- Search --}}
        <div class="relative flex-1">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search categories..."
                class="w-full pl-10 pr-9 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all"
            />
            @if($search)
                <button wire:click="$set('search', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            @endif
        </div>

        {{-- Filter pills --}}
        <div class="flex items-center gap-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-1">
            @foreach(['all' => 'All', 'with-subs' => 'With Subs', 'empty' => 'Empty'] as $value => $label)
                <button
                    wire:click="$set('filter', '{{ $value }}')"
                    class="px-3 py-1.5 text-sm rounded-lg font-medium transition-all {{ $filter === $value ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                >{{ $label }}</button>
            @endforeach
        </div>

        {{-- Per-page --}}
        <div class="flex items-center gap-2">
            <label class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">Per page</label>
            <select
                wire:model.live="perPage"
                class="py-2 pl-3 pr-8 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all appearance-none bg-no-repeat"
                style="background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E\"); background-position: right 0.5rem center; background-size: 1rem;"
            >
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>

    {{-- Empty state --}}
    @if($mainCategories->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-16 text-center">
            @if($search || $filter !== 'all')
                <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">No results found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your search or filter.</p>
            @else
                <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">No categories yet</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create your first category to start organizing expenses.</p>
            @endif
        </div>

    @else
        {{-- Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-900/40 border-b border-gray-200 dark:border-gray-700">
                        <th class="w-10 px-4 py-3"></th>
                        <th class="text-left px-4 py-3 font-semibold text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">Name</th>
                        <th class="text-left px-4 py-3 font-semibold text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 hidden md:table-cell">Description</th>
                        <th class="text-center px-4 py-3 font-semibold text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 w-28">Subcategories</th>
                        <th class="text-right px-4 py-3 font-semibold text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 w-36">Actions</th>
                    </tr>
                </thead>

                @foreach($mainCategories as $category)
                    @php
                        $subs = $category->rootSubCategoriesWithChildren;
                        $totalSubs = $category->subCategories->count();
                        $accentColor = $category->color ?? '#6b7280';
                        $isEven = $loop->even;
                    @endphp
                    <tbody x-data="{ expanded: true }" class="group/body">
                        {{-- Main category row --}}
                        <tr
                            class="border-b border-gray-100 dark:border-gray-700/60 hover:bg-blue-50/40 dark:hover:bg-blue-900/10 transition-colors duration-150 cursor-pointer relative {{ $isEven ? 'bg-slate-50 dark:bg-gray-900/30' : 'bg-white dark:bg-gray-800' }}"
                            @click="expanded = !expanded"
                            style="border-left: 3px solid {{ $accentColor }}"
                        >
                            <td class="px-4 py-3.5 text-center w-10">
                                <div class="flex items-center justify-center">
                                    <svg
                                        class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200 ease-in-out"
                                        :class="expanded ? 'rotate-90' : ''"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    @if($category->color)
                                        <span class="w-2.5 h-2.5 rounded-full ring-2 ring-white dark:ring-gray-800 flex-shrink-0" style="background-color: {{ $category->color }}"></span>
                                    @else
                                        <span class="w-2.5 h-2.5 rounded-full bg-gray-300 dark:bg-gray-600 ring-2 ring-white dark:ring-gray-800 flex-shrink-0"></span>
                                    @endif
                                    <a
                                        href="{{ route('categories.report', $category) }}"
                                        wire:navigate
                                        class="font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                        @click.stop
                                    >{{ $category->name }}</a>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 hidden md:table-cell max-w-xs">
                                <span class="text-gray-500 dark:text-gray-400 line-clamp-1 text-sm">{{ $category->description ?: '—' }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-flex items-center justify-center min-w-[1.5rem] h-6 px-1.5 rounded-md text-xs font-semibold tabular-nums
                                    {{ $totalSubs > 0 ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500' }}">
                                    {{ $totalSubs }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5" @click.stop>
                                <div class="flex items-center justify-end gap-0.5">
                                    <button
                                        wire:click="startCreateSubCategory({{ $category->id }})"
                                        class="p-1.5 text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors"
                                        title="Add subcategory"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                    <button
                                        wire:click="startEditMainCategory({{ $category->id }})"
                                        class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors"
                                        title="Edit"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </button>
                                    <button
                                        wire:click="startDeleteMainCategory({{ $category->id }})"
                                        class="p-1.5 text-gray-400 hover:text-red-500 dark:hover:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                        title="Delete"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Subcategory rows --}}
                        <tr x-show="expanded" x-collapse.duration.150ms class="border-b border-gray-100 dark:border-gray-700/40">
                            <td colspan="5" class="p-0">
                                @if($subs->isNotEmpty())
                                    <table class="w-full text-sm">
                                        <tbody>
                                            @include('livewire.categories.partials.subcategory-table-rows', [
                                                'subCategories' => $subs,
                                                'depth' => 1,
                                                'accentColor' => $accentColor,
                                                'parentIsEven' => $isEven,
                                            ])
                                        </tbody>
                                    </table>
                                @else
                                    <div class="py-3 pl-16 text-xs text-gray-400 dark:text-gray-500 italic bg-gray-50/60 dark:bg-gray-900/20">
                                        No subcategories — <button wire:click="startCreateSubCategory({{ $category->id }})" class="text-blue-500 hover:text-blue-600 dark:hover:text-blue-400 underline underline-offset-2 transition-colors">add one</button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                @endforeach
            </table>
        </div>

        {{-- Pagination --}}
        @if($mainCategories->hasPages())
            <div class="mt-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                {{-- Info --}}
                <p class="text-sm text-gray-500 dark:text-gray-400 order-2 sm:order-1">
                    Showing <span class="font-medium text-gray-700 dark:text-gray-300">{{ $mainCategories->firstItem() }}</span>
                    to <span class="font-medium text-gray-700 dark:text-gray-300">{{ $mainCategories->lastItem() }}</span>
                    of <span class="font-medium text-gray-700 dark:text-gray-300">{{ $mainCategories->total() }}</span> categories
                </p>

                {{-- Page controls --}}
                <div class="flex items-center gap-1 order-1 sm:order-2">
                    {{-- Previous --}}
                    @if($mainCategories->onFirstPage())
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </span>
                    @else
                        <button
                            wire:click="previousPage"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:border-blue-300 dark:hover:border-blue-700 hover:text-blue-600 dark:hover:text-blue-400 transition-all"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                    @endif

                    {{-- Page numbers --}}
                    @foreach($mainCategories->getUrlRange(max(1, $mainCategories->currentPage() - 2), min($mainCategories->lastPage(), $mainCategories->currentPage() + 2)) as $page => $url)
                        @if($page == $mainCategories->currentPage())
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-sm font-semibold text-white bg-blue-600 border border-blue-600 shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <button
                                wire:click="gotoPage({{ $page }})"
                                class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:border-blue-300 dark:hover:border-blue-700 hover:text-blue-600 dark:hover:text-blue-400 transition-all"
                            >{{ $page }}</button>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if($mainCategories->hasMorePages())
                        <button
                            wire:click="nextPage"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:border-blue-300 dark:hover:border-blue-700 hover:text-blue-600 dark:hover:text-blue-400 transition-all"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @else
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-300 dark:text-gray-600 cursor-not-allowed bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    @endif
                </div>
            </div>
        @else
            {{-- Single page info --}}
            <div class="mt-4 text-center text-sm text-gray-400 dark:text-gray-500">
                {{ $mainCategories->total() }} {{ Str::plural('category', $mainCategories->total()) }} total
            </div>
        @endif
    @endif

    <x-filament-actions::modals />
</div>
