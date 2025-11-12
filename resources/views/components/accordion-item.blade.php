@props(['title', 'id', 'badge' => null, 'badgeCount' => null])

<div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
    <button
        type="button"
        @click="$store.accordion.open = $store.accordion.open === '{{ $id }}' ? null : '{{ $id }}'"
        class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-gray-50 transition-colors duration-200"
    >
        <div class="flex items-center gap-3">
            <span class="text-lg font-bold text-gray-800">{{ $title }}</span>
            
            @if($badge === 'error' && $badgeCount > 0)
                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full">
                    {{ $badgeCount }} {{ $badgeCount === 1 ? 'error' : 'errores' }}
                </span>
            @endif
        </div>
        
        <svg 
            class="w-6 h-6 text-gray-500 transition-transform duration-200"
            :class="{ 'rotate-180': $store.accordion.open === '{{ $id }}' }"
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    
    <div
        x-show="$store.accordion.open === '{{ $id }}'"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="px-6 py-6 border-t border-gray-200 bg-gray-50"
        style="display: none;"
    >
        {{ $slot }}
    </div>
</div>