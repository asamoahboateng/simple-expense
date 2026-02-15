@foreach($subCategories as $sub)
    @php $hasChildren = $sub->children->count() > 0; @endphp

    <div class="py-0.5" style="padding-left: {{ $depth * 1.25 }}rem" x-data="{ open: true }">
        <div class="flex items-center justify-between group rounded-lg px-2 py-1.5 -mx-1 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
            <div class="flex items-center gap-2 min-w-0">
                {{-- Tree connector --}}
                <span class="text-gray-300 dark:text-gray-600 text-xs select-none font-mono leading-none">
                    @if($loop->last)&#x2514;@else&#x251C;@endif&#x2500;
                </span>

                @if($hasChildren)
                    <button @click.stop="open = !open" class="flex items-center gap-1.5 min-w-0">
                        <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-150 flex-shrink-0" :class="open && 'rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        <span class="text-sm text-gray-800 dark:text-gray-200 truncate">{{ $sub->name }}</span>
                        <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 px-1.5 py-0.5 rounded flex-shrink-0">
                            {{ $sub->children->count() }}
                        </span>
                    </button>
                @else
                    <div class="flex items-center gap-1.5 min-w-0">
                        <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $sub->name }}</span>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                <button wire:click="startEditSubCategory({{ $sub->id }})" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors" title="Edit">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                </button>
                {{ $this->deleteSubCategoryAction(['id' => $sub->id]) }}
            </div>
        </div>

        @if($hasChildren)
            <div x-show="open" x-collapse>
                @include('livewire.categories.partials.subcategory-tree', [
                    'subCategories' => $sub->children,
                    'depth' => $depth + 1,
                ])
            </div>
        @endif
    </div>
@endforeach
