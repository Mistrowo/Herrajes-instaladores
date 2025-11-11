@props(['title', 'id', 'open' => false, 'badge' => null, 'badgeCount' => null])

@php
    $isOpen = $open;
    $hasBadge = $badge && $badgeCount > 0;
@endphp

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <button
        type="button"
        @click="toggle('{{ $id }}')"
        class="w-full px-6 py-4 text-left font-bold text-gray-900 flex items-center justify-between hover:bg-gray-50 transition"
        :class="{ 'bg-blue-50': $store.accordion.open === '{{ $id }}' }"
    >
        <span class="flex items-center gap-2">
            {{ $title }}
            @if($hasBadge)
                <span class="px-2 py-1 text-xs font-medium rounded-full
                    {{ $badge === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                    {{ $badgeCount }}
                </span>
            @endif
        </span>
        <svg x-show="$store.accordion.open !== '{{ $id }}'" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
        <svg x-show="$store.accordion.open === '{{ $id }}'" class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
        </svg>
    </button>

    <div x-show="$store.accordion.open === '{{ $id }}'" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="px-6 pb-6">
        {{ $slot }}
    </div>
</div>