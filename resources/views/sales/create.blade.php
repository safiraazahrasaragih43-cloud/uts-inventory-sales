@extends('layouts.app')

@section('title', 'Transaksi Kasir Baru')
@section('page_title', 'Kasir Penjualan Pakaian (Point of Sales)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-blush-200/80 shadow-sm">
        <div class="mb-6 pb-4 border-b border-blush-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Form Transaksi Kasir</h3>
                <p class="text-xs text-gray-500">Stok pakaian akan otomatis dikurangi dengan penguncian database (<span class="font-mono text-blush-700">lockForUpdate</span>).</p>
            </div>
            <a href="{{ route('sales.index') }}" class="text-xs font-semibold text-gray-500 hover:text-blush-700">
                ← Riwayat Penjualan
            </a>
        </div>

        <form action="{{ route('sales.store') }}" method="POST" id="salesForm" class="space-y-6">
            @csrf

            <!-- Customer & Payment Metadata -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4 rounded-2xl bg-blush-50/40 border border-blush-200">
                <!-- Customer Selection -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-semibold text-gray-700">Pilih Pelanggan (Customer)</label>
                        <a href="{{ route('customers.create') }}" target="_blank" class="text-[10px] text-blush-600 hover:text-blush-800 font-semibold underline" title="Buka form pendaftaran pelanggan baru">+ Baru</a>
                    </div>
                    <select name="customer_id" id="customerSelect" class="w-full px-3 py-2 bg-white rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                        <option value="" data-name="Pelanggan Walk-in">Walk-in Customer (Non-Member)</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" data-name="{{ $customer->name }}" data-phone="{{ $customer->phone }}" {{ (old('customer_id', request('customer_id')) == $customer->id) ? 'selected' : '' }}>
                                {{ $customer->name }} {{ $customer->phone ? '• ' . $customer->phone : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Customer Name (Manual / Auto-filled) -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Pelanggan di Nota</label>
                    <input type="text" name="customer_name" id="customerNameInput" value="{{ old('customer_name', 'Pelanggan Walk-in') }}" placeholder="contoh: Kak Sheila" class="w-full px-3 py-2 bg-white rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Metode Pembayaran <span class="text-rose-500">*</span></label>
                    <select name="payment_method" required class="w-full px-3 py-2 bg-white rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                        <option value="Tunai" {{ old('payment_method') == 'Tunai' ? 'selected' : '' }}>Tunai / Cash</option>
                        <option value="QRIS" {{ old('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS (BCA/Mandiri/ShopeePay)</option>
                        <option value="Transfer Bank" {{ old('payment_method') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="Debit/Kartu Kredit" {{ old('payment_method') == 'Debit/Kartu Kredit' ? 'selected' : '' }}>Debit / Kartu Kredit</option>
                    </select>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Transaksi</label>
                    <input type="text" name="notes" value="{{ old('notes') }}" placeholder="opsional: fitting room, gift wrap..." class="w-full px-3 py-2 bg-white rounded-xl border border-blush-200 focus:outline-none focus:ring-2 focus:ring-blush-400 text-xs text-gray-800">
                </div>
            </div>

            <!-- Items Table Section -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-600">Daftar Item Pakaian yang Dibeli:</h4>
                    <button type="button" id="addItemBtn" class="px-3 py-1.5 rounded-xl bg-blush-100 hover:bg-blush-200 text-blush-900 text-xs font-semibold flex items-center space-x-1 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Tambah Baris Baju</span>
                    </button>
                </div>

                <div class="border border-blush-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                    <table class="w-full text-xs text-left" id="itemsTable">
                        <thead class="bg-blush-50 text-blush-900 font-semibold border-b border-blush-200">
                            <tr>
                                <th class="py-3 px-4 w-1/2">Pilih Busana / SKU</th>
                                <th class="py-3 px-3 text-right">Harga Satuan</th>
                                <th class="py-3 px-3 text-center w-24">Jumlah (Qty)</th>
                                <th class="py-3 px-3 text-right">Subtotal</th>
                                <th class="py-3 px-3 text-center w-12">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody" class="divide-y divide-blush-100">
                            <!-- Dynamic row template will be injected here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total Summary & Submit -->
            <div class="p-6 rounded-2xl bg-gradient-to-r from-blush-50 via-white to-blush-100 border border-blush-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <span class="text-xs text-gray-500 font-medium block">Total Pembayaran (Estimasi):</span>
                    <span class="text-2xl font-bold text-blush-900" id="grandTotalDisplay">Rp 0</span>
                    <p class="text-[11px] text-gray-400 mt-0.5">🔒 Nilai total final dikalkulasi murni dari database server untuk keamanan.</p>
                </div>

                <div class="flex items-center space-x-3 w-full sm:w-auto">
                    <a href="{{ route('sales.index') }}" class="w-1/2 sm:w-auto text-center px-4 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 text-xs font-semibold transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="w-1/2 sm:w-auto btn-blush-primary px-8 py-3 rounded-xl text-xs font-bold shadow-md">
                        Proses Pembayaran Kasir
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>

<script>
    // Product catalog data available to JS for dynamic row selection
    const productsData = @json($products);

    let rowIndex = 0;

    function addRow(selectedProductId = '', qty = 1) {
        const tbody = document.getElementById('itemsBody');
        const tr = document.createElement('tr');
        tr.id = `row-${rowIndex}`;
        tr.className = 'hover:bg-blush-50/20';

        let optionsHtml = '<option value="">-- Pilih Pakaian --</option>';
        productsData.forEach(p => {
            const isOutOfStock = p.stock <= 0;
            const stockLabel = isOutOfStock ? ' [HABIS - 0 pcs]' : ` [Tersedia: ${p.stock} pcs]`;
            const selected = (p.id == selectedProductId) ? 'selected' : '';
            optionsHtml += `<option value="${p.id}" data-price="${p.selling_price}" data-stock="${p.stock}" ${selected} ${isOutOfStock ? 'disabled' : ''}>${p.sku} - ${p.name} - Rp ${Number(p.selling_price).toLocaleString('id-ID')}${stockLabel}</option>`;
        });

        tr.innerHTML = `
            <td class="py-3 px-4">
                <select name="items[${rowIndex}][product_id]" required class="product-select w-full px-3 py-2 bg-blush-50/30 rounded-xl border border-blush-200 text-xs text-gray-800 focus:outline-none focus:ring-1 focus:ring-blush-400" onchange="updateRow(${rowIndex})">
                    ${optionsHtml}
                </select>
                <div class="stock-warning text-[10px] text-rose-600 mt-1 hidden" id="warning-${rowIndex}"></div>
            </td>
            <td class="py-3 px-3 text-right font-medium text-gray-700" id="unitPrice-${rowIndex}">
                Rp 0
            </td>
            <td class="py-3 px-3 text-center">
                <input type="number" min="1" name="items[${rowIndex}][quantity]" value="${qty}" required class="qty-input w-20 px-2 py-1.5 text-center bg-blush-50/30 rounded-lg border border-blush-200 text-xs font-bold text-gray-800 focus:outline-none focus:ring-1 focus:ring-blush-400" onchange="updateRow(${rowIndex})" onkeyup="updateRow(${rowIndex})">
            </td>
            <td class="py-3 px-3 text-right font-bold text-blush-800" id="subtotal-${rowIndex}">
                Rp 0
            </td>
            <td class="py-3 px-3 text-center">
                <button type="button" onclick="removeRow(${rowIndex})" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        updateRow(rowIndex);
        rowIndex++;
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

        const select = row.querySelector('.product-select');
        const qtyInput = row.querySelector('.qty-input');
        const unitPriceElem = document.getElementById(`unitPrice-${idx}`);
        const subtotalElem = document.getElementById(`subtotal-${idx}`);
        const warningElem = document.getElementById(`warning-${idx}`);

        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
            const qty = parseInt(qtyInput.value) || 0;

            unitPriceElem.innerText = 'Rp ' + price.toLocaleString('id-ID');
            subtotalElem.innerText = 'Rp ' + (price * qty).toLocaleString('id-ID');

            if (qty > stock) {
                warningElem.innerText = `⚠️ Permintaan (${qty}) melebihi stok yang ada (${stock})!`;
                warningElem.classList.remove('hidden');
            } else {
                warningElem.classList.add('hidden');
            }
        } else {
            unitPriceElem.innerText = 'Rp 0';
            subtotalElem.innerText = 'Rp 0';
            warningElem.classList.add('hidden');
        }

        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let total = 0;
        const rows = document.querySelectorAll('#itemsBody tr');
        rows.forEach(r => {
            const select = r.querySelector('.product-select');
            const qtyInput = r.querySelector('.qty-input');
            if (select && select.value && qtyInput) {
                const opt = select.options[select.selectedIndex];
                const price = parseFloat(opt.getAttribute('data-price')) || 0;
                const qty = parseInt(qtyInput.value) || 0;
                total += (price * qty);
            }
        });

        document.getElementById('grandTotalDisplay').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.getElementById('addItemBtn').addEventListener('click', () => {
        addRow();
    });

    // Sync customer selection with customer name input
    const customerSelect = document.getElementById('customerSelect');
    const customerNameInput = document.getElementById('customerNameInput');
    if (customerSelect && customerNameInput) {
        customerSelect.addEventListener('change', function() {
            const selectedOpt = this.options[this.selectedIndex];
            if (selectedOpt && selectedOpt.value) {
                const name = selectedOpt.getAttribute('data-name');
                if (name) customerNameInput.value = name;
            } else {
                customerNameInput.value = 'Pelanggan Walk-in';
            }
        });
    }

    // Add first row on load & sync pre-selected customer
    window.addEventListener('DOMContentLoaded', () => {
        addRow();
        if (customerSelect && customerSelect.value && customerNameInput) {
            const selectedOpt = customerSelect.options[customerSelect.selectedIndex];
            if (selectedOpt && selectedOpt.value) {
                const name = selectedOpt.getAttribute('data-name');
                if (name && (customerNameInput.value === 'Pelanggan Walk-in' || !customerNameInput.value)) {
                    customerNameInput.value = name;
                }
            }
        }
    });
</script>
@endsection
