@extends('layouts.admin')

@section('title', 'Tambah Kucing')
@section('header', 'Tambah Data Kucing Baru')

@section('content')

<div class="max-w-2xl">
    <a href="{{ route('admin.cats.index') }}"
       class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1 mb-6">
        ← Kembali
    </a>

    <div class="bg-white rounded-xl shadow-sm p-6">
        {{--
            enctype="multipart/form-data" WAJIB ada jika form punya input file/foto
            Tanpa ini, foto tidak akan terupload
        --}}
        <form action="{{ route('admin.cats.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b">Informasi Dasar</h3>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Pemilik <span class="text-red-500">*</span>
                </label>
                <select name="customer_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <option value="">-- Pilih Pemilik --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}"
                            {{ old('customer_id', request('customer_id')) == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
                @error('customer_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-form-input
                    name="name"
                    label="Nama Kucing"
                    :value="old('name')"
                    placeholder="Contoh: Mochi"
                    :required="true"
                />
                <x-form-input
                    name="breed"
                    label="Ras"
                    :value="old('breed')"
                    placeholder="Contoh: Persia, Anggora, DSH"
                />
                <x-form-select
                    name="gender"
                    label="Jenis Kelamin"
                    :selected="old('gender')"
                    :options="['male' => 'Jantan', 'female' => 'Betina']"
                />
                <x-form-input
                    name="date_of_birth"
                    label="Tanggal Lahir"
                    type="date"
                    :value="old('date_of_birth')"
                />
                <x-form-input
                    name="weight"
                    label="Berat (kg)"
                    type="number"
                    :value="old('weight')"
                    placeholder="Contoh: 3.5"
                    help="Isi dengan angka desimal, contoh: 3.5"
                />
                <x-form-input
                    name="fur_color"
                    label="Warna Bulu"
                    :value="old('fur_color')"
                    placeholder="Contoh: Orange, Putih, Abu-abu"
                />
            </div>

            {{-- Checkbox sterilisasi --}}
            <div class="mb-4 flex items-center gap-2">
                <input type="hidden" name="is_sterilized" value="0">
                <input type="checkbox" id="is_sterilized" name="is_sterilized" value="1"
                    {{ old('is_sterilized') ? 'checked' : '' }}
                    class="w-4 h-4 text-orange-600 rounded">
                <label for="is_sterilized" class="text-sm text-gray-700">
                    Sudah disterilkan / dikebiri
                </label>
            </div>

            <h3 class="font-semibold text-gray-700 mb-4 pb-2 border-b mt-6">Foto & Catatan Kesehatan</h3>

            {{-- Upload foto --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Foto Kucing
                </label>
                <input type="file" name="photo" accept="image/*"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, WebP. Maks 2MB.</p>
                @error('photo')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-form-input
                    name="last_vaccination_date"
                    label="Tanggal Vaksin Terakhir"
                    type="date"
                    :value="old('last_vaccination_date')"
                />
                <x-form-input
                    name="next_vaccination_date"
                    label="Jadwal Vaksin Berikutnya"
                    type="date"
                    :value="old('next_vaccination_date')"
                />
            </div>

            <div class="mb-4">
                <label for="allergies" class="block text-sm font-medium text-gray-700 mb-1">
                    Alergi
                </label>
                <textarea id="allergies" name="allergies" rows="2"
                    placeholder="Contoh: Alergi ikan tuna, sensitif terhadap shampo tertentu"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                >{{ old('allergies') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="special_notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Catatan Khusus
                </label>
                <textarea id="special_notes" name="special_notes" rows="3"
                    placeholder="Contoh: Suka gigit saat dimandikan, takut pengering rambut..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                >{{ old('special_notes') }}</textarea>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                    class="bg-orange-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-orange-700 transition">
                    Simpan Data Kucing
                </button>
                <a href="{{ route('admin.cats.index') }}"
                   class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
