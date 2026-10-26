<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Display public boutique catalog for visitors without login.
     */
    public function index(Request $request)
    {
        $categories = Category::withCount('products')->get();

        $query = Product::with('category');

        // Search by keyword
        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('sku', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by stock status
        if ($request->filled('status')) {
            if ($request->status === 'ready') {
                $query->where('stock', '>', 0);
            } elseif ($request->status === 'empty') {
                $query->where('stock', '<=', 0);
            }
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderBy('selling_price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('selling_price', 'desc');
        } elseif ($sort === 'name') {
            $query->orderBy('name', 'asc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(15)->withQueryString();

        // Contact info for direct ordering via WhatsApp
        $storeContact = [
            'name' => 'BlushBoutique Official',
            'phone' => '6281234567890',
            'instagram' => '@blushboutique.id',
            'address' => 'Jl. Kenanga Indah No. 18, Bandung',
            'hours' => 'Senin - Sabtu: 09.00 - 20.00 WIB'
        ];

        return view('catalog.index', compact('categories', 'products', 'storeContact'));
    }

    /**
     * Display public product detail modal / view.
     */
    public function show(Product $product)
    {
        $product->load('category');

        $waNumber = '6281234567890';
        $message = "Halo BlushBoutique, saya tertarik memesan produk berikut:%0A"
            . "- Produk: {$product->name}%0A"
            . "- SKU: {$product->sku}%0A"
            . "- Harga: {$product->formatted_selling_price}%0A"
            . "Apakah stok masih tersedia?";
        $waUrl = "https://wa.me/{$waNumber}?text={$message}";

        return view('catalog.show', compact('product', 'waUrl'));
    }
}
