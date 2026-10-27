<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlushBoutiqueTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test Public Catalog can be accessed without login.
     */
    public function test_public_catalog_accessible_without_login(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('BlushBoutique');
        $response->assertSee('Pesan Sekarang');
        $response->assertSee('Tambah ke Keranjang');
        $response->assertSee('fashion-img-container');
    }

    /**
     * Test Public Product Detail page.
     */
    public function test_public_product_detail_page(): void
    {
        $product = Product::first();
        $response = $this->get('/catalog/' . $product->id);
        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee('Pesan Sekarang (COD / Bayar di Tempat)');
    }

    /**
     * Test Staff and Admin authentication.
     */
    public function test_admin_can_login_and_access_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@blushboutique.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $admin = User::where('email', 'admin@blushboutique.com')->first();
        $this->actingAs($admin)->get('/dashboard')->assertStatus(200)->assertSee('Nilai Aset Modal Inventory');
    }

    /**
     * Test Staff Role Restrictions: Staff cannot access Admin master data / reports.
     */
    public function test_staff_cannot_access_categories_or_reports(): void
    {
        $staff = User::where('email', 'staff@blushboutique.com')->first();

        // Staff can access dashboard and products
        $this->actingAs($staff)->get('/dashboard')->assertStatus(200);
        $this->actingAs($staff)->get('/products')->assertStatus(200);

        // Staff is blocked with 403 on categories and reports
        $this->actingAs($staff)->get('/categories')->assertStatus(403);
        $this->actingAs($staff)->get('/reports/sales')->assertStatus(403);
        $this->actingAs($staff)->get('/reports/inventory')->assertStatus(403);
    }

    /**
     * Test Sale transaction logic: Decrements stock and validates stock.
     */
    public function test_sale_transaction_decrements_stock_and_rejects_insufficient_stock(): void
    {
        $staff = User::where('email', 'staff@blushboutique.com')->first();
        $product = Product::first();
        $initialStock = $product->stock;

        // Valid sale
        $response = $this->actingAs($staff)->post('/sales', [
            'customer_name' => 'Customer Test',
            'payment_method' => 'Tunai',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ]
            ]
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals($initialStock - 2, $product->fresh()->stock);

        // Invalid sale: quantity exceeds stock
        $currentStock = $product->fresh()->stock;
        $excessQuantity = $currentStock + 50;

        $responseFailed = $this->actingAs($staff)->post('/sales', [
            'customer_name' => 'Customer Greedy',
            'payment_method' => 'Tunai',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => $excessQuantity,
                ]
            ]
        ]);

        $responseFailed->assertSessionHas('error');
        // Stock should remain unchanged
        $this->assertEquals($currentStock, $product->fresh()->stock);
    }

    /**
     * Test Purchase restock transaction logic: Increments stock.
     */
    public function test_purchase_transaction_increments_stock(): void
    {
        $admin = User::where('email', 'admin@blushboutique.com')->first();
        $supplier = Supplier::first();
        $product = Product::first();
        $initialStock = $product->stock;

        $response = $this->actingAs($admin)->post('/purchases', [
            'supplier_id' => $supplier->id,
            'purchase_date' => now()->toDateString(),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 15,
                    'unit_price' => 120000,
                ]
            ]
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals($initialStock + 15, $product->fresh()->stock);
    }

    /**
     * Test Admin can create product with uploaded image.
     */
    public function test_admin_can_upload_product_with_image(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $admin = User::where('email', 'admin@blushboutique.com')->first();
        $category = Category::first();
        // 1x1 valid gif bytes to satisfy image validator without requiring GD library
        $gifBytes = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        $fakeImage = \Illuminate\Http\UploadedFile::fake()->createWithContent('dress.gif', $gifBytes);

        $response = $this->actingAs($admin)->post('/products', [
            'category_id' => $category->id,
            'name' => 'Dress Baru Cantik',
            'sku' => 'BLUSH-TEST-IMG-99',
            'description' => 'Baju dress dengan foto produk',
            'purchase_price' => 150000,
            'selling_price' => 290000,
            'stock' => 10,
            'minimum_stock' => 3,
            'image' => $fakeImage,
        ]);

        $response->assertRedirect(route('products.index'));
        $product = Product::where('sku', 'BLUSH-TEST-IMG-99')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->image);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($product->image);
    }

    /**
     * Test Checkout COD page loads properly with and without direct product.
     */
    public function test_checkout_cod_page_renders_properly(): void
    {
        $response = $this->get('/checkout');
        $response->assertStatus(200);
        $response->assertSee('Alamat Pengiriman Paket');
        $response->assertSee('Metode Pembayaran');
        $response->assertSee('COD (Cash on Delivery');

        $product = Product::first();
        $responseWithDirect = $this->get('/checkout?product_id=' . $product->id);
        $responseWithDirect->assertStatus(200);
        $responseWithDirect->assertSee($product->name);
        $responseWithDirect->assertSee('Pilih Ukuran Busana:');
    }

    /**
     * Test Checkout COD form submission saves transaction and automatically decrements stock via DB::transaction.
     */
    public function test_checkout_cod_stores_sale_and_sale_items_and_decrements_product_stock(): void
    {
        $product = Product::where('stock', '>=', 5)->first();
        $initialStock = $product->stock;
        $orderQty = 2;

        $response = $this->post('/checkout', [
            'customer_name' => 'Safira Pelanggan',
            'phone' => '081299988877',
            'address' => 'Jl. Anggrek Merah No. 45, Bandung',
            'notes' => 'Tolong telepon saat sampai di depan gerbang',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => $orderQty,
                    'size' => 'M',
                ]
            ]
        ]);

        // Harus redirect ke checkout.success/{invoice}
        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Pastikan stok produk berkurang tepat 2 pcs
        $product->refresh();
        $this->assertEquals($initialStock - $orderQty, $product->stock);

        // Pastikan record di tabel sales dibuat
        $this->assertDatabaseHas('sales', [
            'customer_name' => 'Safira Pelanggan',
            'payment_method' => 'COD (Bayar di Tempat)',
            'total_amount' => $product->selling_price * $orderQty,
        ]);

        // Ambil sale transaksi COD yang baru dibuat
        $latestSale = \App\Models\Sale::where('customer_name', 'Safira Pelanggan')->latest('id')->first();
        $this->assertNotNull($latestSale);
        $this->assertStringStartsWith('SLS-COD-', $latestSale->invoice_number);

        // Pastikan record di tabel sale_items dibuat
        $this->assertDatabaseHas('sale_items', [
            'sale_id' => $latestSale->id,
            'product_id' => $product->id,
            'quantity' => $orderQty,
            'unit_price' => $product->selling_price,
            'subtotal' => $product->selling_price * $orderQty,
        ]);

        // Pastikan halaman faktur sukses dapat diakses
        $successResponse = $this->get('/checkout/success/' . $latestSale->invoice_number);
        $successResponse->assertStatus(200);
        $successResponse->assertSee($latestSale->invoice_number);
        $successResponse->assertSee('Safira Pelanggan');
        $successResponse->assertSee($product->name);
    }

    /**
     * Test Checkout COD rejects when product stock is insufficient.
     */
    public function test_checkout_cod_rejects_when_product_stock_is_insufficient(): void
    {
        $product = Product::first();
        $product->update(['stock' => 1]);

        $response = $this->post('/checkout', [
            'customer_name' => 'Safira Pelanggan',
            'phone' => '081299988877',
            'address' => 'Jl. Anggrek Merah No. 45, Bandung',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10, // melebihi sisa stok 1
                    'size' => 'M',
                ]
            ]
        ]);

        $response->assertSessionHas('error');
        $product->refresh();
        $this->assertEquals(1, $product->stock); // stok tidak berubah
    }
}
