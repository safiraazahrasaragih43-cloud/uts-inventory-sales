@extends('layouts.app')

@section('title', 'Catat Pembelian Stok Baru')
@section('page_title', 'Restock / Pembelian Pakaian dari Supplier')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-blush-200/80 shadow-sm">
        <div class="mb-6 pb-4 border-b border-blush-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Formulir Restock Inventaris</h3>
                <p class="text-xs text-gray-500">Stok produk yang dipilih akan otomatis bertambah secara konsisten ke dalam sistem.</p>
            </div>
            <a href="{{ route('purchases.index') }}" class="text-xs font-semibold text-gray-500 hover:text-blush-700">
                ← Riwayat Pembelian
            </a>
        </div>

        <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm" class="space-y-6">
            @csrf

            <!-- Supplier & Purchase Date Metadata -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 rounded-2xl bg-purple-50/40 border border-purple-200">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Supplier <span class="text-rose-500">*</span></label>
                    <select name="supplier_id" required class="w-full px-3 py-2 bg-white rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }} ({{ $s->phone }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Pembelian <span class="text-rose-500">*</span></label>
                    <input type="date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" required class="w-full px-3 py-2 bg-white rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan / No. PO Supplier</label>
                    <input type="text" name="notes" value="{{ old('notes') }}" placeholder="opsional: restock season baru..." class="w-full px-3 py-2 bg-white rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                </div>
            </div>

            <!-- Items Table Section -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-600">Daftar Pakaian yang Dibeli / Masuk:</h4>
                    <button type="button" id="addItemBtn" class="px-3 py-1.5 rounded-xl bg-purple-100 hover:bg-purple-200 text-purple-900 text-xs font-semibold flex items-center space-x-1 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Tambah Baris Baju</span>
                    </button>
                </div>

                <div class="border border-purple-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                    <table class="w-full text-xs text-left" id="itemsTable">
                        <thead class="bg-purple-50 text-purple-900 font-semibold border-b border-purple-200">
                            <tr>
                                <th class="py-3 px-4 w-5/12">Pilih Pakaian / SKU</th>
                                <th class="py-3 px-3 text-center w-24">Jumlah Masuk</th>
                                <th class="py-3 px-3 text-right w-36">Harga Beli Satuan (Rp)</th>
                                <th class="py-3 px-3 text-right">Subtotal</th>
                                <th class="py-3 px-3 text-center w-12">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody" class="divide-y divide-purple-100">
                            <!-- Injected by JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total Summary & Submit -->
            <div class="p-6 rounded-2xl bg-gradient-to-r from-purple-50 via-white to-purple-100 border border-purple-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <span class="text-xs text-gray-500 font-medium block">Total Nilai Pembelian:</span>
                    <span class="text-2xl font-bold text-purple-900" id="grandTotalDisplay">Rp 0</span>
                    <p class="text-[11px] text-gray-500 mt-0.5">📦 Stok produk akan otomatis bertambah saat tombol simpan ditekan.</p>
                </div>

                <div class="flex items-center space-x-3 w-full sm:w-auto">
                    <a href="{{ route('purchases.index') }}" class="w-1/2 sm:w-auto text-center px-4 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-xs font-semibold transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="w-1/2 sm:w-auto px-8 py-3 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold shadow-md shadow-purple-300 transition-colors">
                        Simpan & Tambah Stok
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>

<script>
    const productsData = @json($products);
    let rowIndex = 0;

    function addRow(selectedProductId = '', qty = 10) {
        const tbody = document.getElementById('itemsBody');
        const tr = document.createElement('tr');
        tr.id = `row-${rowIndex}`;

        let optionsHtml = '<option value="">-- Pilih Busana --</option>';
        productsData.forEach(p => {
            const selected = (p.id == selectedProductId) ? 'selected' : '';
            optionsHtml += `<option value="${p.id}" data-cost="${p.purchase_price}" ${selected}>${p.sku} - ${p.name} (Stok Saat Ini: ${p.stock})</option>`;
        });

        tr.innerHTML = `
            <td class="py-3 px-4">
                <select name="items[${rowIndex}][product_id]" required class="product-select w-full px-3 py-2 bg-purple-50/30 rounded-xl border border-purple-200 text-xs text-gray-800 focus:outline-none focus:ring-1 focus:ring-purple-400" onchange="onProductChange(${rowIndex})">
                    ${optionsHtml}
                </select>
            </td>
            <td class="py-3 px-3 text-center">
                <input type="number" min="1" name="items[${rowIndex}][quantity]" value="${qty}" required class="qty-input w-20 px-2 py-1.5 text-center bg-purple-50/30 rounded-lg border border-purple-200 text-xs font-bold text-gray-800" onchange="updateRow(${rowIndex})" onkeyup="updateRow(${rowIndex})">
            </td>
            <td class="py-3 px-3 text-right">
                <input type="number" min="0" step="500" name="items[${rowIndex}][unit_price]" value="0" required class="price-input w-32 px-2 py-1.5 text-right bg-purple-50/30 rounded-lg border border-purple-200 text-xs font-bold text-gray-800" onchange="updateRow(${rowIndex})" onkeyup="updateRow(${rowIndex})">
            </td>
            <td class="py-3 px-3 text-right font-bold text-purple-900" id="subtotal-${rowIndex}">
                Rp 0
            </td>
            <td class="py-3 px-3 text-center">
                <button type="button" onclick="removeRow(${rowIndex})" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        onProductChange(rowIndex);
        rowIndex++;
    }

    function onProductChange(idx) {
        const row = document.getElementById(`row-${idx}`);
        if (!row) return;

        const select = row.querySelector('.product-select');
        const priceInput = row.querySelector('.price-input');

        const opt = select.options[select.selectedIndex];
        if (opt && opt.value) {
            const cost = parseFloat(opt.getAttribute('data-cost')) || 0;
            priceInput.value = cost;
        }
        updateRow(idx);
    }

    function removeRow(idx) {
        const row = document.getElementById(`row-${idx}`);
        if (row) {
            row.remove();
            calculateGrandTotal();
        }
    }

    function updateRow(idx) {
        const row = document.getElementById(`row-${idx}`);
        if (!row) return;

        const qtyInput = row.querySelector('.qty-input');
        const priceInput = row.querySelector('.price-input');
        const subtotalElem = document.getElementById(`subtotal-${idx}`);

        const qty = parseInt(qtyInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;

        subtotalElem.innerText = 'Rp ' + (qty * price).toLocaleString('id-ID');
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let total = 0;
        const rows = document.querySelectorAll('#itemsBody tr');
        rows.forEach(r => {
            const qtyInput = r.querySelector('.qty-input');
            const priceInput = r.querySelector('.price-input');
            if (qtyInput && priceInput) {
                const qty = parseInt(qtyInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                total += (qty * price);
            }
        });

        document.getElementById('grandTotalDisplay').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.getElementById('addItemBtn').addEventListener('click', () => {
        addRow();
    });

    window.addEventListener('DOMContentLoaded', () => {
        addRow();
    });
</script>
@endsection
