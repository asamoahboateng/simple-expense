@foreach($subCategories as $sub)
    @php
        $hasChildren = $sub->children->count() > 0;
        $paddingLeft = ($depth * 1.25) + 1;
        $accent = $accentColor ?? '#6b7280';
        // Alternate within each group; flip stripe if parent row is even so sub-rows contrast
        $subIsEven = isset($parentIsEven) ? ($parentIsEven ? $loop->odd : $loop->even) : $loop->even;
        $subBg = $subIsEven ? 'bg-slate-50/70 dark:bg-gray-900/20' : 'bg-white dark:bg-gray-800';
    @endphp

    @if($hasChildren)
        <tr
            x-data="{ open: true }"
            class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors duration-100 group {{ $subBg }}"
            style="border-left: 3px solid {{ $accent }}33"
        >
    @else
        <tr
            class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors duration-100 group {{ $subBg }}"
            style="border-left: 3px solid {{ $accent }}33"
        >
    @endif
            {{-- Expand / leaf indicator --}}
            <td class="py-2.5 w-10 text-center" style="padding-left: {{ $paddingLeft }}rem">
                @if($hasChildren)
                    <button @click.stop="open = !open" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-3 h-3 transition-transform duration-150" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                @else
                    <span class="block w-3 h-3 mx-auto opacity-30">
                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                @endif
            </td>

            {{-- Name --}}
            <td class="px-4 py-2.5">
                <div class="flex items-center gap-2.5">
                    @if($hasChildren)
                        <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                    @else
                        <svg class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    @endif
                    <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $sub->name }}</span>
                    @if($hasChildren)
                        <span class="text-xs tabular-nums bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 px-1.5 py-0.5 rounded font-medium">
                            {{ $sub->children->count() }}
                        </span>
                    @endif
                </div>
            </td>

            {{-- Description --}}
            <td class="px-4 py-2.5 hidden md:table-cell max-w-xs">
                <span class="text-xs text-gray-400 dark:text-gray-500 line-clamp-1">{{ $sub->description ?? '' }}</span>
            </td>

            {{-- Subcategory count placeholder (align with header) --}}
            <td class="px-4 py-2.5 w-28"></td>

            {{-- Actions --}}
            <td class="px-4 py-2.5 w-36">
                <div class="flex items-center justify-end gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button
                        wire:click="startEditSubCategory({{ $sub->id }})"
                        class="p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors"
                        title="Edit"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    </button>
                    <button
                        wire:click="startDeleteSubCategory({{ $sub->id }})"
                        class="p-1.5 text-gray-400 hover:text-red-500 dark:hover:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                        title="Delete"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </td>
        </tr>

        @if($hasChildren)
            <tr x-show="open" x-collapse.duration.100ms>
                <td colspan="5" class="p-0">
                    <table class="w-full text-sm">
                        <tbody>
                            @include('livewire.categories.partials.subcategory-table-rows', [
                                'subCategories' => $sub->children,
                                'depth' => $depth + 1,
                                'accentColor' => $accentColor,
                                'parentIsEven' => $subIsEven,
                            ])
                        </tbody>
                    </table>
                </td>
            </tr>
        @endif
@endforeach
