{{--
    Komponen input field yang reusable.
    Cara pakai: <x-form-input name="name" label="Nama" :value="old('name')" />
    Props yang tersedia: name, label, type, value, placeholder, required, help
--}}

@props([
    'name',
    'label'       => '',
    'type'        => 'text',
    'value'       => '',
    'placeholder' => '',
    'required'    => false,
    'help'        => '',
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400
               {{ $errors->has($name) ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
    >

    {{-- Tampilkan pesan error validasi jika ada --}}
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror

    {{-- Teks bantuan opsional di bawah input --}}
    @if($help)
        <p class="mt-1 text-xs text-gray-500">{{ $help }}</p>
    @endif
</div>
