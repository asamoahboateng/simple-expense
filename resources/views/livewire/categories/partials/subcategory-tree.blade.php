@foreach($subCategories as $sub)
    <div class="py-1.5" style="padding-left: {{ $depth * 1.5 }}rem">
        <div class="flex items-center justify-between group border-l-2 border-gray-200 dark:border-gray-600 pl-4">
            <div class="flex items-center gap-2">
                @if($sub->children->count())
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                @else
                    <svg class="w-4 h-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                @endif
                <span class="text-sm text-gray-800 dark:text-gray-200">{{ $sub->name }}</span>
                @if($sub->description)
                    <span class="text-xs text-gray-400">&mdash; {{ Str::limit($sub->description, 40) }}</span>
                @endif
                @if($sub->children->count())
                    <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-500 px-1.5 py-0.5 rounded">
                        {{ $sub->children->count() }}
                    </span>
                @endif
            </div>
            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                {{ $this->editSubCategoryAction(['id' => $sub->id]) }}
                {{ $this->deleteSubCategoryAction(['id' => $sub->id]) }}
            </div>
        </div>

        @if($sub->children->count())
            @include('livewire.categories.partials.subcategory-tree', [
                'subCategories' => $sub->children,
                'depth' => $depth + 1,
            ])
        @endif
    </div>
@endforeach
