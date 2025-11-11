<div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
    <label class="font-medium text-gray-700">{{ $label }}</label>
    <div class="flex gap-4">
        <label class="flex items-center">
            <input type="radio" name="{{ $name }}" value="1" {{ $value ?? false ? 'checked' : '' }} class="w-4 h-4 text-green-600">
            <span class="ml-2 text-sm text-green-700">Sí</span>
        </label>
        <label class="flex items-center">
            <input type="radio" name="{{ $name }}" value="0" {{ ($value ?? true) == false ? 'checked' : '' }} class="w-4 h-4 text-red-600">
            <span class="ml-2 text-sm text-red-700">No</span>
        </label>
    </div>
</div>