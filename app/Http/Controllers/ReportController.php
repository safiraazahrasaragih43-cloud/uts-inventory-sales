<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Laporan Penjualan & Analisis Keuntungan (Admin Only)
     */
    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $sales = Sale::with(['user', 'items.product'])
            ->whereDate('sale_date', '>=', $startDate)
            ->whereDate('sale_date', '<=', $endDate)
            ->latest('sale_date')
            ->get();

        $totalRevenue = $sales->sum('total_amount');
        $totalTransactions = $sales->count();

        // Hitung estimasi modal (COGS) dan laba kotor murni di server
        $saleIds = $sales->pluck('id');
        $saleItems = SaleItem::with('product')->whereIn('sale_id', $saleIds)->get();

        $totalCogs = 0;
        foreach ($saleItems as $item) {
            $modalPerItem = $item->product ? $item->product->purchase_price : 0;
            $totalCogs += ($modalPerItem * $item->quantity);
        }

        $grossProfit = $totalRevenue - $totalCogs;

        return view('reports.sales', compact(
            'sales',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalTransactions',
            'totalCogs',
            'grossProfit'
        ));
    }

    /**
     * Laporan Stok & Valuasi Inventory (Admin Only)
     */
    public function inventory(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('filter_status')) {
            if ($request->filter_status === 'low') {
                $query->whereColumn('stock', '<=', 'minimum_stock')->where('stock', '>', 0);
            } elseif ($request->filter_status === 'out') {
                $query->where('stock', '<=', 0);
            } elseif ($request->filter_status === 'safe') {
                $query->whereColumn('stock', '>', 'minimum_stock');
            }
        }

        $products = $query->orderBy('stock', 'asc')->get();

        $totalStockUnits = $products->sum('stock');
        $totalInventoryCost = $products->sum(function ($p) {
            return $p->stock * $p->purchase_price;
        });
        $totalPotentialRevenue = $products->sum(function ($p) {
            return $p->stock * $p->selling_price;
        });

        $lowStockCount = $products->filter(function ($p) {
            return $p->stock <= $p->minimum_stock;
        })->count();

        return view('reports.inventory', compact(
            'products',
            'totalStockUnits',
            'totalInventoryCost',
            'totalPotentialRevenue',
            'lowStockCount'
        ));
    }
}
