<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // General Counts
        $totalProducts = Product::count();
        $totalStockUnits = Product::sum('stock');
        $lowStockProductsCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $totalCustomers = Customer::count();

        // Sales Metrics
        $totalSalesCount = Sale::count();
        $totalSalesAmount = Sale::sum('total_amount');
        $todaySalesAmount = Sale::whereDate('sale_date', today())->sum('total_amount');
        $todaySalesCount = Sale::whereDate('sale_date', today())->count();

        // Low stock items (critical alert list)
        $lowStockItems = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // Recent Sales
        $recentSales = Sale::with(['user', 'customer', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        // Recent Purchases
        $recentPurchases = Purchase::with(['supplier', 'user', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        // Inventory Valuation (Only for Admin)
        $totalInventoryAsset = 0;
        if ($user->isAdmin()) {
            $totalInventoryAsset = Product::selectRaw('SUM(stock * purchase_price) as total_asset')->value('total_asset') ?? 0;
        }

        // Data Khusus Role Customer
        $customerRecord = null;
        $customerSales = collect();
        $customerTotalSpent = 0;
        $customerTotalItems = 0;
        $featuredProducts = collect();

        if ($user->isCustomer()) {
            $customerRecord = Customer::where('email', $user->email)
                ->orWhere('name', 'like', '%' . explode(' ', $user->name)[0] . '%')
                ->first();

            if ($customerRecord) {
                $customerSales = Sale::where('customer_id', $customerRecord->id)
                    ->with(['items.product', 'user'])
                    ->latest('sale_date')
                    ->get();
            } else {
                $customerSales = Sale::where('customer_name', 'like', '%' . $user->name . '%')
                    ->with(['items.product', 'user'])
                    ->latest('sale_date')
                    ->get();
            }

            $customerTotalSpent = $customerSales->sum('total_amount');
            $customerTotalItems = $customerSales->sum(function ($s) {
                return $s->items->sum('quantity');
            });
            $featuredProducts = Product::with('category')->where('stock', '>', 0)->latest()->take(4)->get();
        }

        return view('dashboard.index', compact(
            'user',
            'totalProducts',
            'totalStockUnits',
            'lowStockProductsCount',
            'totalCategories',
            'totalSuppliers',
            'totalCustomers',
            'totalSalesCount',
            'totalSalesAmount',
            'todaySalesAmount',
            'todaySalesCount',
            'lowStockItems',
            'recentSales',
            'recentPurchases',
            'totalInventoryAsset',
            'customerRecord',
            'customerSales',
            'customerTotalSpent',
            'customerTotalItems',
            'featuredProducts'
        ));
    }
}
