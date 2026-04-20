@extends('layouts.admin')
@section('title', 'Kasir')
@section('header', '🛒 Kasir / POS')

@section('content')

<div class="grid grid-cols-5 gap-4 h-[calc(100vh-120px)]">

    {{-- Panel Kiri: Pencarian & Daftar Produk --}}
    <div class="col-span-3 flex flex-col gap-3">

        {{-- Search produk --}}
        <div class="bg-white rounded-xl shadow-sm p-3">
            <input type="text" id="product-search"
                placeholder="🔍 Ketik nama produk atau scan barcode..."
                class="w-full px-4 py-3 border-2 border-orange-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400 focus:border-orange-500"
                autocomplete="off">
        </div>

        {{-- Hasil pencarian --}}
        <div id="search-results"
             class="bg-white rounded-xl shadow-sm overflow-y-auto flex-1 p-2">
            <div class="text-center py-8 text-gray-400" id="search-placeholder">
                <p class="text-4xl mb-2">🔍</p>
                <p class="text-sm">Cari produk di atas untuk mulai transaksi</p>
            </div>
            <div id="product-list" class="grid grid-cols-2 gap-2 hidden"></div>
        </div>
    </div>

    {{-- Panel Kanan: Keranjang Belanja --}}
    <div class="col-span-2 flex flex-col gap-3">

        {{-- Pilih pelanggan (opsional) --}}
        <div class="bg-white rounded-xl shadow-sm p-3">
            <label class="text-xs text-gray-500 mb-1 block">Pelanggan (opsional)</label>
            <select id="customer-select"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400">
                <option value="">👤 Pelanggan Umum (Walk-in)</option>
                @foreach($customers as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Keranjang --}}
        <div class="bg-white rounded-xl shadow-sm flex-1 flex flex-col overflow-hidden">
            <div class="px-4 py-3 border-b flex justify-between items-center">
                <h3 class="font-semibold text-gray-700 text-sm">Keranjang</h3>
                <button onclick="clearCart()"
                    class="text-xs text-red-500 hover:text-red-700">🗑️ Kosongkan</button>
            </div>

            {{-- Item dalam keranjang --}}
            <div id="cart-items" class="flex-1 overflow-y-auto p-3 space-y-2">
                <div id="cart-empty" class="text-center py-8 text-gray-400">
                    <p class="text-3xl mb-2">🛒</p>
                    <p class="text-sm">Keranjang kosong</p>
                </div>
            </div>

            {{-- Summary & Checkout --}}
            <div class="border-t p-3 space-y-2">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span id="summary-subtotal">Rp 0</span>
                </div>

                {{-- Input diskon --}}
                <div class="flex items-center gap-2">
                    <label class="text-xs text-gray-500 whitespace-nowrap">Diskon (Rp)</label>
                    <input type="number" id="discount-input" value="0" min="0"
                        class="flex-1 px-2 py-1 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-orange-400"
                        oninput="updateSummary()">
                </div>

                <div class="flex justify-between font-bold text-base pt-1 border-t">
                    <span>Total</span>
                    <span id="summary-total" class="text-orange-600">Rp 0</span>
                </div>

                {{-- Input pembayaran --}}
                <div class="space-y-2">
                    <div>
                        <label class="text-xs text-gray-500">Metode Pembayaran</label>
                        <div class="grid grid-cols-5 gap-1 mt-1">
                            @foreach(['cash' => '💵', 'transfer' => '🏦', 'qris' => '📱', 'debit' => '💳', 'credit' => '💳'] as $m => $icon)
                            <label class="cursor-pointer">
                                <input type="radio" name="pm" value="{{ $m }}" class="sr-only peer"
                                    {{ $m === 'cash' ? 'checked' : '' }}>
                                <div class="text-center py-1 border-2 rounded text-xs transition
                                            peer-checked:border-orange-500 peer-checked:bg-orange-50
                                            border-gray-200 hover:border-orange-300">
                                    {{ $icon }}<br>
                                    <span class="text-xs">{{ strtoupper($m) }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="text-xs text-gray-500">Uang Diterima (Rp)</label>
                        <input type="number" id="paid-input" value="0" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400 mt-1"
                            oninput="updateChange()">
                        {{-- Tombol quick amount untuk cash --}}
                        <div class="flex gap-1 mt-1" id="quick-amounts">
                            @foreach([10000, 20000, 50000, 100000] as $amt)
                            <button type="button" onclick="setAmount({{ $amt }})"
                                class="flex-1 text-xs bg-gray-100 hover:bg-orange-100 py-1 rounded text-gray-600">
                                {{ number_format($amt/1000) }}rb
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <div id="change-display" class="hidden flex justify-between font-semibold text-green-600 bg-green-50 rounded-lg px-3 py-2 text-sm">
                        <span>Kembalian</span>
                        <span id="change-amount">Rp 0</span>
                    </div>
                </div>

                <button id="checkout-btn" onclick="processCheckout()"
                    class="w-full bg-orange-600 text-white py-3 rounded-xl font-semibold text-sm
                           hover:bg-orange-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition"
                    disabled>
                    ✅ Proses Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal sukses transaksi --}}
<div id="success-modal"
     class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-8 max-w-sm w-full text-center shadow-2xl">
        <div class="text-6xl mb-3">✅</div>
        <h2 class="text-xl font-bold text-gray-800 mb-1">Transaksi Berhasil!</h2>
        <p id="modal-invoice" class="text-sm text-gray-500 mb-2"></p>
        <div id="modal-change" class="hidden bg-green-50 rounded-xl p-3 mb-4">
            <p class="text-sm text-gray-600">Kembalian</p>
            <p id="modal-change-amount" class="text-2xl font-bold text-green-600"></p>
        </div>
        <div class="flex gap-2 mt-4">
            <button onclick="viewReceipt()"
                class="flex-1 bg-orange-600 text-white py-2 rounded-lg text-sm hover:bg-orange-700">
                🧾 Lihat Struk
            </button>
            <button onclick="newTransaction()"
                class="flex-1 bg-gray-100 text-gray-700 py-2 rounded-lg text-sm hover:bg-gray-200">
                🛒 Transaksi Baru
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ============================================================
// STATE
// ============================================================
let cart        = [];  // Array item di keranjang
let lastSaleId  = null;
let searchTimer = null;

// ============================================================
// PENCARIAN PRODUK
// ============================================================
document.getElementById('product-search').addEventListener('input', function() {
    clearTimeout(searchTimer);
    const q = this.value.trim();

    if (q.length < 1) {
        document.getElementById('product-list').classList.add('hidden');
        document.getElementById('search-placeholder').classList.remove('hidden');
        return;
    }

    // Debounce 300ms agar tidak spam request saat mengetik cepat
    searchTimer = setTimeout(() => {
        fetch(`{{ route('admin.products.search') }}?q=${encodeURIComponent(q)}`)
            .then(r => r.json())
            .then(products => renderProducts(products));
    }, 300);
});

function renderProducts(products) {
    const list = document.getElementById('product-list');
    const ph   = document.getElementById('search-placeholder');

    if (products.length === 0) {
        list.classList.add('hidden');
        ph.innerHTML = '<p class="text-4xl mb-2">😿</p><p class="text-sm">Produk tidak ditemukan.</p>';
        ph.classList.remove('hidden');
        return;
    }

    ph.classList.add('hidden');
    list.classList.remove('hidden');
    list.innerHTML = products.map(p => `
        <div onclick="addToCart(${p.id}, '${escStr(p.name)}', ${p.sell_price}, ${p.stock})"
             class="border rounded-xl p-3 cursor-pointer hover:border-orange-400 hover:bg-orange-50 transition
                    ${p.stock === 0 ? 'opacity-50 pointer-events-none' : ''}">
            <p class="font-medium text-gray-800 text-sm leading-tight">${p.name}</p>
            <p class="text-orange-600 font-bold text-sm mt-1">Rp ${fmt(p.sell_price)}</p>
            <p class="text-xs text-gray-400">Stok: ${p.stock} ${p.unit ?? ''}</p>
            ${p.stock === 0 ? '<span class="text-xs text-red-500">Stok habis</span>' : ''}
        </div>
    `).join('');
}

// ============================================================
// KERANJANG
// ============================================================
function addToCart(id, name, price, maxStock) {
    const existing = cart.find(i => i.id === id);

    if (existing) {
        if (existing.qty >= maxStock) {
            alert(`Stok ${name} hanya tersisa ${maxStock}.`);
            return;
        }
        existing.qty++;
    } else {
        cart.push({ id, name, price, qty: 1, discount: 0, maxStock });
    }
    renderCart();
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
}

function updateQty(id, qty) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    const newQty = parseInt(qty);
    if (newQty < 1) { removeFromCart(id); return; }
    if (newQty > item.maxStock) {
        alert(`Stok hanya tersisa ${item.maxStock}.`);
        return;
    }
    item.qty = newQty;
    renderCart();
}

function updateItemDiscount(id, disc) {
    const item = cart.find(i => i.id === id);
    if (item) { item.discount = parseFloat(disc) || 0; renderCart(); }
}

function clearCart() {
    if (cart.length > 0 && !confirm('Kosongkan keranjang?')) return;
    cart = [];
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cart-items');
    const empty     = document.getElementById('cart-empty');
    const btn       = document.getElementById('checkout-btn');

    if (cart.length === 0) {
        container.innerHTML = '';
        container.appendChild(empty);
        empty.style.display = 'block';
        btn.disabled = true;
        updateSummary();
        return;
    }

    empty.style.display = 'none';
    container.innerHTML = cart.map(item => `
        <div class="border rounded-lg p-2 bg-gray-50">
            <div class="flex justify-between items-start mb-1">
                <p class="text-xs font-medium text-gray-800 leading-tight flex-1 mr-2">${item.name}</p>
                <button onclick="removeFromCart(${item.id})"
                    class="text-red-400 hover:text-red-600 text-xs flex-shrink-0">✕</button>
            </div>
            <div class="flex items-center gap-2">
                <input type="number" value="${item.qty}" min="1" max="${item.maxStock}"
                    onchange="updateQty(${item.id}, this.value)"
                    class="w-14 px-2 py-1 border border-gray-300 rounded text-xs text-center focus:ring-1 focus:ring-orange-400">
                <span class="text-xs text-gray-500">×</span>
                <span class="text-xs text-gray-700">Rp ${fmt(item.price)}</span>
                <span class="text-xs font-bold text-orange-600 ml-auto">
                    Rp ${fmt((item.price - item.discount) * item.qty)}
                </span>
            </div>
        </div>
    `).join('');

    btn.disabled = false;
    updateSummary();
}

// ============================================================
// KALKULASI
// ============================================================
function getSubtotal() {
    return cart.reduce((sum, i) => sum + (i.price - i.discount) * i.qty, 0);
}

function updateSummary() {
    const sub     = getSubtotal();
    const disc    = parseFloat(document.getElementById('discount-input').value) || 0;
    const total   = Math.max(0, sub - disc);

    document.getElementById('summary-subtotal').textContent = 'Rp ' + fmt(sub);
    document.getElementById('summary-total').textContent    = 'Rp ' + fmt(total);

    // Auto-fill paid_amount dengan total agar tidak perlu input manual saat non-cash
    const pm = document.querySelector('input[name="pm"]:checked')?.value;
    if (pm !== 'cash') {
        document.getElementById('paid-input').value = total;
    }
    updateChange();
}

function updateChange() {
    const disc  = parseFloat(document.getElementById('discount-input').value) || 0;
    const total = Math.max(0, getSubtotal() - disc);
    const paid  = parseFloat(document.getElementById('paid-input').value) || 0;
    const chg   = Math.max(0, paid - total);
    const el    = document.getElementById('change-display');

    document.getElementById('change-amount').textContent = 'Rp ' + fmt(chg);

    if (paid > 0 && paid >= total) {
        el.classList.remove('hidden');
    } else {
        el.classList.add('hidden');
    }
}

function setAmount(amount) {
    const disc  = parseFloat(document.getElementById('discount-input').value) || 0;
    const total = Math.max(0, getSubtotal() - disc);
    // Set ke kelipatan amount yang cukup untuk menutup total
    const rounded = Math.ceil(total / amount) * amount;
    document.getElementById('paid-input').value = Math.max(amount, rounded);
    updateChange();
}

// ============================================================
// CHECKOUT
// ============================================================
function processCheckout() {
    if (cart.length === 0) { alert('Keranjang kosong!'); return; }

    const disc  = parseFloat(document.getElementById('discount-input').value) || 0;
    const total = Math.max(0, getSubtotal() - disc);
    const paid  = parseFloat(document.getElementById('paid-input').value) || 0;
    const pm    = document.querySelector('input[name="pm"]:checked').value;

    if (paid < total) {
        alert('Uang yang diterima kurang dari total belanja!');
        document.getElementById('paid-input').focus();
        return;
    }

    const btn = document.getElementById('checkout-btn');
    btn.disabled    = true;
    btn.textContent = '⏳ Memproses...';

    const payload = {
        customer_id:     document.getElementById('customer-select').value || null,
        payment_method:  pm,
        paid_amount:     paid,
        discount_amount: disc,
        items: cart.map(i => ({
            product_id: i.id,
            quantity:   i.qty,
            unit_price: i.price,
            discount:   i.discount,
        })),
    };

    fetch('{{ route("admin.sales.store") }}', {
        method:  'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept':       'application/json',
        },
        body: JSON.stringify(payload),
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled    = false;
        btn.textContent = '✅ Proses Pembayaran';

        if (data.success) {
            lastSaleId = data.sale_id;
            document.getElementById('modal-invoice').textContent = 'Invoice: ' + data.invoice_number;

            const chg = parseFloat(data.change);
            if (chg > 0) {
                document.getElementById('modal-change').classList.remove('hidden');
                document.getElementById('modal-change-amount').textContent = 'Rp ' + fmt(chg);
            } else {
                document.getElementById('modal-change').classList.add('hidden');
            }
            document.getElementById('success-modal').classList.remove('hidden');
        } else {
            alert('Gagal: ' + data.message);
        }
    })
    .catch(() => {
        btn.disabled    = false;
        btn.textContent = '✅ Proses Pembayaran';
        alert('Terjadi kesalahan. Coba lagi.');
    });
}

function viewReceipt() {
    if (lastSaleId) window.location.href = `/admin/sales/${lastSaleId}`;
}

function newTransaction() {
    cart = [];
    document.getElementById('success-modal').classList.add('hidden');
    document.getElementById('product-search').value = '';
    document.getElementById('discount-input').value = '0';
    document.getElementById('paid-input').value = '0';
    document.getElementById('customer-select').value = '';
    document.getElementById('product-list').classList.add('hidden');
    document.getElementById('search-placeholder').classList.remove('hidden');
    document.getElementById('search-placeholder').innerHTML =
        '<p class="text-4xl mb-2">🔍</p><p class="text-sm">Cari produk di atas untuk mulai transaksi</p>';
    renderCart();
    document.getElementById('product-search').focus();
}

// ============================================================
// UTILS
// ============================================================
function fmt(n) { return Math.round(n).toLocaleString('id-ID'); }
function escStr(s) { return s.replace(/'/g, "\\'").replace(/"/g, '\\"'); }

// Fokus ke input search saat halaman dibuka
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('product-search').focus();
});
</script>
@endpush
