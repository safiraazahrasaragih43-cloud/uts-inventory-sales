<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        // Eager load customer, user, and items.product to prevent N+1 query problem
        $query = Sale::with(['user', 'customer', 'items.product']);

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('invoice_number', 'like', "%{$keyword}%")
                  ->orWhere('customer_name', 'like', "%{$keyword}%")
                  ->orWhereHas('customer', function ($cq) use ($keyword) {
                      $cq->where('name', 'like', "%{$keyword}%")
                         ->orWhere('phone', 'like', "%{$keyword}%");
                  });
            });
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('sale_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('sale_date', '<=', $request->date_to);
        }

        $sales = $query->latest('sale_date')->latest('id')->paginate(10)->withQueryString();

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        // Ambil semua produk aktif beserta kategorinya
        $products = Product::with('category')->orderBy('name')->get();
        // Ambil daftar pelanggan terdaftar untuk opsi transaksi
        $customers = Customer::orderBy('name')->get();

        return view('sales.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:100',
            'payment_method' => 'required|string|in:Tunai,Transfer Bank,QRIS,Debit/Kartu Kredit',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ], [
            'customer_id.exists' => 'Data pelanggan yang dipilih tidak valid.',
            'items.required' => 'Minimal harus ada 1 item pakaian yang dibeli.',
            'items.min' => 'Minimal harus ada 1 item pakaian yang dibeli.',
            'items.*.product_id.required' => 'Produk pakaian wajib dipilih.',
            'items.*.quantity.min' => 'Jumlah kuantitas item minimal 1.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        try {
            $sale = DB::transaction(function () use ($request) {
                $totalAmount = 0;
                $processedItems = [];

                foreach ($request->items as $itemData) {
                    $qty = intval($itemData['quantity']);

                    if ($qty <= 0) {
                        throw new \Exception("Jumlah item harus lebih dari 0.");
                    }

                    // Kunci baris data produk dengan lockForUpdate() untuk mencegah race condition
                    $product = Product::where('id', $itemData['product_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    // VALIDASI STOK: Tolak transaksi jika stok tidak mencukupi
                    if ($product->stock < $qty) {
                        throw new \Exception("Penjualan ditolak: Stok untuk produk '{$product->name}' (SKU: {$product->sku}) tidak mencukupi! Stok saat ini: {$product->stock}, Permintaan: {$qty}.");
                    }

                    // PERHITUNGAN TOTAL HARGA MURNI DARI SERVER
                    // Mengambil selling_price langsung dari database untuk mencegah manipulasi client
                    $unitPrice = $product->selling_price;
                    $subtotal = $unitPrice * $qty;
                    $totalAmount += $subtotal;

                    // Logika Transaksi: Kurangi stok produk
                    $product->decrement('stock', $qty);

                    $processedItems[] = [
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ];
                }

                // Buat invoice penjualan unik
                $invoiceNumber = 'SLS-' . date('Ymd') . '-' . strtoupper(Str::random(5));

                // Tentukan nama pelanggan dan customer_id
                $customerId = $request->customer_id ?: null;
                $customerName = 'Pelanggan Walk-in';

                if ($customerId) {
                    $selectedCustomer = Customer::find($customerId);
                    $customerName = $selectedCustomer ? $selectedCustomer->name : ($request->customer_name ?: 'Pelanggan Walk-in');
                } elseif ($request->filled('customer_name')) {
                    $customerName = trim($request->customer_name);
                }

                $sale = Sale::create([
                    'invoice_number' => $invoiceNumber,
                    'user_id' => auth()->id(),
                    'customer_id' => $customerId,
                    'customer_name' => $customerName,
                    'sale_date' => now(),
                    'total_amount' => $totalAmount,
                    'payment_method' => $request->payment_method,
                    'notes' => $request->notes,
                ]);

                foreach ($processedItems as $item) {
                    $sale->items()->create($item);
                }

                return $sale;
            });

            return redirect()->route('sales.show', $sale)
                ->with('success', "Transaksi penjualan {$sale->invoice_number} berhasil dicatat! Stok pakaian telah diperbarui.");

        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['user', 'customer', 'items.product.category']);

        return view('sales.show', compact('sale'));
    }
}
