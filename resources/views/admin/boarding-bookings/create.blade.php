@extends('layouts.admin')
@section('title', 'Booking Penitipan Baru')
@section('header', 'Buat Booking Penitipan')

@section('content')

<div class="max-w-2xl">
    <a href="{{ route('admin.boarding-bookings.index') }}"
       class="text-gray-500 text-sm flex items-center gap-1 mb-6">← Kembali</a>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.boarding-bookings.store') }}" method="POST">
            @csrf

            {{-- Pilih pelanggan --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Pelanggan <span class="text-red-500">*</span>
                </label>
                <select name="customer_id" id="customer-select" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400
                           {{ $errors->has('customer_id') ? 'border-red-400' : '' }}">
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}"
                        {{ old('customer_id', $selectedCustomer?->id) == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                    @endforeach
                </select>
                @error('customer_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pilih kucing (AJAX) --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Kucing <span class="text-red-500">*</span>
                </label>
                <select name="cat_id" id="cat-select" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400
                           {{ $errors->has('cat_id') ? 'border-red-400' : '' }}">
                    <option value="">-- Pilih pelanggan dahulu --</option>
                    @foreach($cats as $cat)
                    <option value="{{ $cat->id }}" {{ old('cat_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ $cat->breed ?? 'mix' }})
                    </option>
                    @endforeach
                </select>
                @error('cat_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pilih kamar berdasarkan tipe --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Kamar <span class="text-red-500">*</span>
                </label>
                <select name="room_id" id="room-select" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400
                           {{ $errors->has('room_id') ? 'border-red-400' : '' }}">
                    <option value="">-- Pilih Kamar --</option>
                    @foreach($roomTypes as $type)
                    <optgroup label="{{ $type->name }} — {{ $type->formatted_price }}/malam">
                        @foreach($type->rooms as $room)
                        <option value="{{ $room->id }}"
                            data-price="{{ $type->price_per_day }}"
                            data-type="{{ $type->name }}"
                            {{ old('room_id') == $room->id ? 'selected' : '' }}
                            {{ $room->status !== 'available' ? 'disabled' : '' }}>
                            {{ $room->room_number }}
                            {{ $room->status !== 'available' ? '(Tidak Tersedia)' : '' }}
                        </option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
                @error('room_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal check-in dan check-out --}}
            <div class="grid grid-cols-2 gap-4">
                <x-form-input
                    name="check_in_date"
                    label="Tanggal Check-in"
                    type="date"
                    :value="old('check_in_date')"
                    :required="true"
                />
                <x-form-input
                    name="check_out_date"
                    label="Tanggal Check-out"
                    type="date"
                    :value="old('check_out_date')"
                    :required="true"
                />
            </div>

            {{-- Preview kalkulasi harga --}}
            <div id="price-preview"
                 class="hidden mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Durasi:</span>
                    <span class="font-medium" id="preview-duration">-</span>
                </div>
                <div class="flex justify-between mt-1">
                    <span class="text-gray-600">Harga/malam:</span>
                    <span class="font-medium" id="preview-price-per-day">-</span>
                </div>
                <div class="flex justify-between mt-1 pt-1 border-t border-orange-200">
                    <span class="text-orange-700 font-semibold">Total Estimasi:</span>
                    <span class="text-orange-700 font-bold text-lg" id="preview-total">-</span>
                </div>
            </div>

            <div class="mb-4">
                <label for="customer_notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Catatan (opsional)
                </label>
                <textarea id="customer_notes" name="customer_notes" rows="2"
                    placeholder="Permintaan khusus, informasi tambahan..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400"
                >{{ old('customer_notes') }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-orange-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-orange-700 transition">
                    Buat Booking
                </button>
                <a href="{{ route('admin.boarding-bookings.index') }}"
                   class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// AJAX: fetch kucing saat pelanggan dipilih
document.getElementById('customer-select').addEventListener('change', function () {
    const catSelect = document.getElementById('cat-select');
    if (!this.value) {
        catSelect.innerHTML = '<option value="">-- Pilih pelanggan dahulu --</option>';
        return;
    }
    fetch(`/admin/cats?customer_id=${this.value}&format=json`)
        .then(r => r.json())
        .then(cats => {
            catSelect.innerHTML = '<option value="">-- Pilih Kucing --</option>';
            if (cats.length === 0) {
                catSelect.innerHTML += '<option disabled>Pelanggan ini belum punya kucing</option>';
                return;
            }
            cats.forEach(c => {
                catSelect.innerHTML += `<option value="${c.id}">${c.name} (${c.breed ?? 'mix'})</option>`;
            });
        });
});

// Hitung estimasi harga real-time saat tanggal atau kamar berubah
function updatePreview() {
    const roomSelect  = document.getElementById('room-select');
    const checkIn     = document.querySelector('[name="check_in_date"]').value;
    const checkOut    = document.querySelector('[name="check_out_date"]').value;
    const preview     = document.getElementById('price-preview');
    const selectedOpt = roomSelect.options[roomSelect.selectedIndex];

    if (!roomSelect.value || !checkIn || !checkOut) {
        preview.classList.add('hidden');
        return;
    }

    const pricePerDay = parseInt(selectedOpt.dataset.price || 0);
    const d1 = new Date(checkIn);
    const d2 = new Date(checkOut);

    // Hitung selisih hari
    const diffDays = Math.max(1, Math.round((d2 - d1) / (1000 * 60 * 60 * 24)));
    const total    = diffDays * pricePerDay;

    document.getElementById('preview-duration').textContent     = diffDays + ' hari';
    document.getElementById('preview-price-per-day').textContent =
        'Rp ' + pricePerDay.toLocaleString('id-ID');
    document.getElementById('preview-total').textContent =
        'Rp ' + total.toLocaleString('id-ID');

    preview.classList.remove('hidden');
}

document.getElementById('room-select').addEventListener('change', updatePreview);
document.querySelector('[name="check_in_date"]').addEventListener('change', updatePreview);
document.querySelector('[name="check_out_date"]').addEventListener('change', updatePreview);
</script>
@endpush
