<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::orderBy('name')->get();

        $query = Purchase::with(['supplier', 'user', 'items.product']);

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where('invoice_number', 'like', "%{$keyword}%")
                  ->orWhereHas('supplier', function ($q) use ($keyword) {
                      $q->where('name', 'like', "%{$keyword}%");
                  });
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }

        $purchases = $query->latest('purchase_date')->latest('id')->paginate(10)->withQueryString();

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ], [
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'purchase_date.required' => 'Tanggal pembelian wajib diisi.',
            'items.required' => 'Minimal pilih 1 produk untuk pembelian.',
            'items.min' => 'Minimal pilih 1 produk untuk pembelian.',
            'items.*.product_id.required' => 'Produk wajib dipilih.',
            'items.*.quantity.min' => 'Jumlah kuantitas minimal 1.',
            'items.*.unit_price.min' => 'Harga beli satuan tidak boleh bernilai negatif.',
        ]);

        try {
            $purchase = DB::transaction(function () use ($request) {
                $totalAmount = 0;
                $processedItems = [];

                foreach ($request->items as $itemData) {
                    $qty = intval($itemData['quantity']);
                    $unitPrice = floatval($itemData['unit_price']);

                    // Kunci baris produk dengan lockForUpdate() untuk menjaga konsistensi stok
                    $product = Product::where('id', $itemData['product_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    // Perhitungan subtotal murni dari server
                    $subtotal = $unitPrice * $qty;
                    $totalAmount += $subtotal;

                    // Logika Transaksi: Menambah stok produk
                    $product->increment('stock', $qty);

                    // Update harga beli master jika diinginkan untuk sinkronisasi harga modal terbaru
                    $product->update(['purchase_price' => $unitPrice]);

                    $processedItems[] = [
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ];
                }

                // Generate invoice pembelian unik
                $invoiceNumber = 'PUR-' . date('Ymd') . '-' . strtoupper(Str::random(5));

                $purchase = Purchase::create([
                    'invoice_number' => $invoiceNumber,
                    'supplier_id' => $request->supplier_id,
                    'user_id' => auth()->id(),
                    'purchase_date' => $request->purchase_date,
                    'total_amount' => $totalAmount,
                    'notes' => $request->notes,
                ]);

                foreach ($processedItems as $item) {
                    $purchase->items()->create($item);
                }

                return $purchase;
            });

            return redirect()->route('purchases.show', $purchase)
                ->with('success', "Transaksi pembelian stok {$purchase->invoice_number} berhasil dicatat. Stok produk telah bertambah!");

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memproses pembelian: ' . $e->getMessage());
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.product.category']);

        return view('purchases.show', compact('purchase'));
    }
}
