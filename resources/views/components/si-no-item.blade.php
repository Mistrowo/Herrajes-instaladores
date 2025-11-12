@props(['label', 'name', 'value' => null])

<div class="space-y-2" x-data="{ selectedValue: '{{ $value ?? '' }}' }">
    <label class="block font-medium text-gray-700">{{ $label }}</label>
    <div class="flex gap-3">
        <button
            type="button"
            @click="selectedValue = 'SI'"
            :class="{
                'bg-green-500 text-white border-green-600 shadow-lg transform scale-105': selectedValue === 'SI',
                'bg-white text-gray-600 border-gray-300 hover:border-green-400 hover:bg-green-50': selectedValue !== 'SI'
            }"
            class="flex-1 px-4 py-2.5 rounded-lg font-semibold transition-all duration-200 border-2 focus:outline-none focus:ring-2 focus:ring-green-300"
        >
            <span class="flex items-center justify-center gap-2">
                <svg x-show="selectedValue === 'SI'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Sí
            </span>
        </button>

        {{-- Botón NO --}}
        <button
            type="button"
            @click="selectedValue = 'NO'"
            :class="{
                'bg-red-500 text-white border-red-600 shadow-lg transform scale-105': selectedValue === 'NO',
                'bg-white text-gray-600 border-gray-300 hover:border-red-400 hover:bg-red-50': selectedValue !== 'NO'
            }"
            class="flex-1 px-4 py-2.5 rounded-lg font-semibold transition-all duration-200 border-2 focus:outline-none focus:ring-2 focus:ring-red-300"
        >
            <span class="flex items-center justify-center gap-2">
                <svg x-show="selectedValue === 'NO'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                No
            </span>
        </button>
    </div>
    
    <input type="hidden" name="{{ $name }}" :value="selectedValue">
</div>