@extends('layouts.admin')
@section('title', 'Input Stok')
@section('header', 'Input Pergerakan Stok')

@section('content')
<div class="max-w-lg">
    <a href="{{ route('admin.stock-movements.index') }}"
       class="text-gray-500 text-sm mb-6 inline-flex items-center gap-1">← Kembali</a>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.stock-movements.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Produk <span class="text-red-500">*</span>
                </label>
                <select name="product_id" required id="product-select"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400">
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $p)
                    <option value="{{ $p->id }}"
                        data-stock="{{ $p->stock }}"
                        data-unit="{{ $p->unit }}"
                        {{ old('product_id', request('product_id')) == $p->id ? 'selected' : '' }}>
                        {{ $p->name }} (Stok: {{ $p->stock }} {{ $p->unit }})
                    </option>
                    @endforeach
                </select>
                @error('product_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Info stok saat ini (dinamis via JS) --}}
            <div id="stock-info" class="hidden mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm">
                Stok saat ini: <span id="current-stock" class="font-bold text-blue-700">-</span>
                <span id="current-unit" class="text-blue-600"></span>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tipe <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['in' => ['📦 Stok Masuk', 'Barang datang dari supplier'], 'out' => ['📤 Stok Keluar', 'Stok rusak/hilang'], 'adjustment' => ['🔧 Penyesuaian', 'Set stok ke nilai tertentu']] as $val => [$lbl, $desc])
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="{{ $val }}" class="sr-only peer"
                            {{ old('type', 'in') === $val ? 'checked' : '' }}>
                        <div class="text-center p-2 border-2 rounded-lg text-xs transition
                                    peer-checked:border-orange-500 peer-checked:bg-orange-50
                                    border-gray-200 hover:border-orange-300">
                            <p class="font-medium">{{ $lbl }}</p>
                            <p class="text-gray-400 mt-0.5">{{ $desc }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <x-form-input name="quantity" label="Jumlah" type="number"
                :value="old('quantity')" :required="true" placeholder="Masukkan jumlah" />

            <div class="mb-4">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea id="notes" name="notes" rows="2"
                    placeholder="Contoh: Restock dari Supplier A, tanggal faktur..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400"
                >{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-orange-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-orange-700">
                    Simpan
                </button>
                <a href="{{ route('admin.stock-movements.index') }}"
                   class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-200">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Tampilkan stok saat ini saat produk dipilih
document.getElementById('product-select').addEventListener('change', function() {
    const opt   = this.options[this.selectedIndex];
    const info  = document.getElementById('stock-info');
    if (!this.value) { info.classList.add('hidden'); return; }
    document.getElementById('current-stock').textContent = opt.dataset.stock;
    document.getElementById('current-unit').textContent  = opt.dataset.unit;
    info.classList.remove('hidden');
});
// Trigger jika ada pre-selected value dari request
document.getElementById('product-select').dispatchEvent(new Event('change'));
</script>
@endpush
