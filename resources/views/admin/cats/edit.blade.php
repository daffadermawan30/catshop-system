@extends('layouts.admin')

@section('title', 'Edit Kucing')
@section('header', 'Edit Data Kucing')

@section('content')

<div class="max-w-2xl">
    <a href="{{ route('admin.cats.show', $cat) }}"
       class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1 mb-6">
        ← Kembali ke detail {{ $cat->name }}
    </a>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.cats.update', $cat) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Informasi Dasar</h3>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pemilik *</label>
                <select name="customer_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}"
                            {{ old('customer_id', $cat->customer_id) == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-form-input name="name" label="Nama Kucing"
                    :value="old('name', $cat->name)" :required="true" />
                <x-form-input name="breed" label="Ras"
                    :value="old('breed', $cat->breed)" />
                <x-form-select name="gender" label="Jenis Kelamin"
                    :selected="old('gender', $cat->gender)"
                    :options="['male' => 'Jantan', 'female' => 'Betina']" />
                <x-form-input name="date_of_birth" label="Tanggal Lahir" type="date"
                    :value="old('date_of_birth', $cat->date_of_birth?->format('Y-m-d'))" />
                <x-form-input name="weight" label="Berat (kg)" type="number"
                    :value="old('weight', $cat->weight)" />
                <x-form-input name="fur_color" label="Warna Bulu"
                    :value="old('fur_color', $cat->fur_color)" />
            </div>

            <div class="mb-4 flex items-center gap-2">
                <input type="hidden" name="is_sterilized" value="0">
                <input type="checkbox" id="is_sterilized" name="is_sterilized" value="1"
                    {{ old('is_sterilized', $cat->is_sterilized) ? 'checked' : '' }}
                    class="w-4 h-4 text-orange-600 rounded">
                <label for="is_sterilized" class="text-sm text-gray-700">Sudah disterilkan / dikebiri</label>
            </div>

            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-6">Foto</h3>

            {{-- Preview foto yang sudah ada --}}
            @if($cat->photo)
            <div class="mb-4 flex items-center gap-4">
                <img src="{{ Storage::url($cat->photo) }}"
                     alt="{{ $cat->name }}"
                     class="w-20 h-20 rounded-lg object-cover border">
                <p class="text-sm text-gray-500">Foto saat ini. Upload baru untuk menggantinya.</p>
            </div>
            @endif

            <div class="mb-4">
                <input type="file" name="photo" accept="image/*"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-orange-50 file:text-orange-700">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto.</p>
            </div>

            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-6">Kesehatan</h3>

            <div class="grid grid-cols-2 gap-4">
                <x-form-input name="last_vaccination_date" label="Vaksin Terakhir" type="date"
                    :value="old('last_vaccination_date', $cat->last_vaccination_date?->format('Y-m-d'))" />
                <x-form-input name="next_vaccination_date" label="Vaksin Berikutnya" type="date"
                    :value="old('next_vaccination_date', $cat->next_vaccination_date?->format('Y-m-d'))" />
            </div>

            <div class="mb-4">
                <label for="allergies" class="block text-sm font-medium text-gray-700 mb-1">Alergi</label>
                <textarea id="allergies" name="allergies" rows="2"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                >{{ old('allergies', $cat->allergies) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="special_notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Khusus</label>
                <textarea id="special_notes" name="special_notes" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                >{{ old('special_notes', $cat->special_notes) }}</textarea>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                    class="bg-orange-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-orange-700 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.cats.show', $cat) }}"
                   class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
