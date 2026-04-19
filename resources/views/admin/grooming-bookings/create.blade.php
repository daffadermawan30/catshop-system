@extends('layouts.admin')

@section('title', 'Booking Grooming Baru')
@section('header', 'Buat Booking Grooming')

@section('content')

<div class="max-w-2xl">
    <a href="{{ route('admin.grooming-bookings.index') }}"
       class="text-gray-500 text-sm flex items-center gap-1 mb-6">← Kembali</a>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.grooming-bookings.store') }}" method="POST" id="booking-form">
            @csrf

            {{-- Pilih pelanggan --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Pelanggan <span class="text-red-500">*</span>
                </label>
                <select name="customer_id" id="customer-select" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400">
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

            {{-- Pilih kucing (muncul setelah pelanggan dipilih) --}}
            <div class="mb-4" id="cat-wrapper">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Kucing <span class="text-red-500">*</span>
                </label>
                <select name="cat_id" id="cat-select" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400">
                    <option value="">-- Pilih pelanggan dahulu --</option>
                    @foreach($cats as $cat)
                    <option value="{{ $cat->id }}"
                        {{ old('cat_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ $cat->breed ?? 'mix' }})
                    </option>
                    @endforeach
                </select>
                @error('cat_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pilih paket --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Paket Grooming <span class="text-red-500">*</span>
                </label>
                <select name="package_id" id="package-select" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400">
                    <option value="">-- Pilih Paket --</option>
                    @foreach($packages as $package)
                    <option value="{{ $package->id }}"
                        data-price="{{ $package->price }}"
                        data-duration="{{ $package->duration_minutes }}"
                        {{ old('package_id') == $package->id ? 'selected' : '' }}>
                        {{ $package->name }} — {{ $package->formatted_price }} ({{ $package->duration_minutes }} menit)
                    </option>
                    @endforeach
                </select>
                @error('package_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror

                {{-- Preview harga paket yang dipilih --}}
                <div id="package-preview" class="hidden mt-2 p-3 bg-orange-50 rounded-lg text-sm">
                    <span class="text-orange-700 font-medium" id="package-price-label"></span>
                    <span class="text-gray-500 ml-2" id="package-duration-label"></span>
                </div>
            </div>

            {{-- Tanggal dan jam booking --}}
            <x-form-input
                name="booking_date"
                label="Tanggal & Waktu Booking"
                type="datetime-local"
                :value="old('booking_date')"
                :required="true"
            />

            {{-- Catatan dari pelanggan --}}
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
                    class="bg-orange-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-orange-700">
                    Buat Booking
                </button>
                <a href="{{ route('admin.grooming-bookings.index') }}"
                   class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Saat pelanggan dipilih, fetch kucing miliknya via AJAX
document.getElementById('customer-select').addEventListener('change', function () {
    const customerId = this.value;
    const catSelect  = document.getElementById('cat-select');

    if (!customerId) {
        catSelect.innerHTML = '<option value="">-- Pilih pelanggan dahulu --</option>';
        return;
    }

    // Fetch kucing milik pelanggan yang dipilih
    // Kita pakai URL route cat index dengan filter customer_id
    fetch(`/admin/cats?customer_id=${customerId}&format=json`)
        .then(r => r.json())
        .then(cats => {
            catSelect.innerHTML = '<option value="">-- Pilih Kucing --</option>';
            if (cats.length === 0) {
                catSelect.innerHTML += '<option disabled>Pelanggan ini belum punya kucing</option>';
                return;
            }
            cats.forEach(cat => {
                catSelect.innerHTML += `<option value="${cat.id}">${cat.name} (${cat.breed ?? 'mix'})</option>`;
            });
        });
});

// Tampilkan preview harga saat paket dipilih
document.getElementById('package-select').addEventListener('change', function () {
    const option   = this.options[this.selectedIndex];
    const preview  = document.getElementById('package-preview');

    if (!this.value) {
        preview.classList.add('hidden');
        return;
    }

    const price    = parseInt(option.dataset.price);
    const duration = option.dataset.duration;

    document.getElementById('package-price-label').textContent =
        'Rp ' + price.toLocaleString('id-ID');
    document.getElementById('package-duration-label').textContent =
        `⏱ Estimasi ${duration} menit`;

    preview.classList.remove('hidden');
});
</script>
@endpush
