<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman form pengisian alamat pengiriman dan ringkasan pesanan COD.
     */
    public function show(Request $request)
    {
        $directProduct = null;
        if ($request->filled('product_id')) {
            $directProduct = Product::with('category')->find($request->product_id);
        }

        return view('checkout.index', compact('directProduct'));
    }

    /**
     * Proses submit form pengiriman COD:
     * - Simpan transaksi ke tabel sales dan sale_items
     * - Kurangi stok produk secara otomatis menggunakan DB::transaction dan lockForUpdate
     * - Buat atau tautkan data pelanggan di tabel customers
     */
    public function store(Request $request)
    {
        // Decode items JSON jika dikirim dalam bentuk serialized string dari frontend
        if ($request->has('items_json') && is_string($request->items_json)) {
            $decoded = json_decode($request->items_json, true);
            if (is_array($decoded) && count($decoded) > 0) {
                $request->merge(['items' => $decoded]);
            }
        }

        // Dukungan jika pelanggan memesan langsung 1 produk lewat input terpisah
        if (!$request->has('items') && $request->filled('direct_product_id')) {
            $request->merge([
                'items' => [
                    [
                        'product_id' => $request->direct_product_id,
                        'quantity' => $request->input('direct_quantity', 1),
                        'size' => $request->input('direct_size', 'All Size'),
                    ]
                ]
            ]);
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'phone' => 'required|string|max:25',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.size' => 'nullable|string|max:20',
        ], [
            'customer_name.required' => 'Nama lengkap penerima wajib diisi.',
            'phone.required' => 'Nomor telepon / WhatsApp penerima wajib diisi.',
            'address.required' => 'Alamat lengkap pengiriman paket COD wajib diisi.',
            'items.required' => 'Tidak ada pakaian yang dipilih untuk dipesan.',
            'items.min' => 'Minimal harus ada 1 produk busana dalam pesanan Anda.',
            'items.*.product_id.required' => 'Produk pakaian tidak valid.',
            'items.*.quantity.min' => 'Jumlah pembelian minimal 1 item.',
        ]);

        try {
            $sale = DB::transaction(function () use ($request) {
                $totalAmount = 0;
                $processedItems = [];

                foreach ($request->items as $itemData) {
                    $qty = intval($itemData['quantity'] ?? 1);
                    if ($qty <= 0) {
                        throw new \Exception("Jumlah kuantitas item minimal 1.");
                    }

                    // Kunci baris data produk dengan lockForUpdate untuk mencegah race condition
                    $product = Product::where('id', $itemData['product_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    // Validasi kecukupan stok produk
                    if ($product->stock < $qty) {
                        throw new \Exception("Maaf, stok untuk produk '{$product->name}' (SKU: {$product->sku}) tidak mencukupi! Sisa stok: {$product->stock} pcs, jumlah diminta: {$qty} pcs.");
                    }

                    // Pengurangan stok otomatis
                    $product->decrement('stock', $qty);

                    $unitPrice = (float) $product->selling_price;
                    $subtotal = $unitPrice * $qty;
                    $totalAmount += $subtotal;

                    $processedItems[] = [
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                        'size' => $itemData['size'] ?? 'All Size',
                    ];
                }

                // Simpan atau perbarui data pelanggan di tabel customers
                $customer = Customer::firstOrCreate(
                    ['phone' => trim($request->phone)],
                    [
                        'name' => trim($request->customer_name),
                        'address' => trim($request->address),
                        'email' => null,
                    ]
                );

                if ($customer && empty($customer->address)) {
                    $customer->update(['address' => trim($request->address)]);
                }

                // Tentukan penanggung jawab sistem transaksi (admin/staff atau user aktif)
                $adminUserId = User::where('role', 'admin')->value('id') ?? 1;
                $userId = auth()->check() ? auth()->id() : $adminUserId;

                // Format nomor invoice pesanan online COD unik
                $invoiceNumber = 'SLS-COD-' . date('Ymd') . '-' . strtoupper(Str::random(5));

                // Format catatan pengiriman
                $shippingNotes = "Alamat Pengiriman COD: " . trim($request->address) . " | No. Telp: " . trim($request->phone);
                if ($request->filled('notes')) {
                    $shippingNotes .= " | Catatan Tambahan: " . trim($request->notes);
                }

                // Simpan ke tabel sales
                $sale = Sale::create([
                    'invoice_number' => $invoiceNumber,
                    'user_id' => $userId,
                    'customer_id' => $customer->id,
                    'customer_name' => trim($request->customer_name),
                    'sale_date' => now(),
                    'total_amount' => $totalAmount,
                    'payment_method' => 'COD (Bayar di Tempat)',
                    'notes' => $shippingNotes,
                ]);

                // Simpan rincian ke tabel sale_items
                foreach ($processedItems as $item) {
                    $sale->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $item['subtotal'],
                    ]);
                }

                return $sale;
            });

            return redirect()->route('checkout.success', $sale->invoice_number)
                ->with('success', "Pesanan COD berhasil dibuat! Nomor faktur Anda: {$sale->invoice_number}");

        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Tampilkan halaman struk nota / bukti pemesanan COD berhasil.
     */
    public function success(Sale $sale)
    {
        $sale->load(['customer', 'items.product.category']);

        return view('checkout.success', compact('sale'));
    }
}
