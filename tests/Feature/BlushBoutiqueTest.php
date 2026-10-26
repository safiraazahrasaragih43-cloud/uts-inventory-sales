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
        $response->assertSee('Koleksi Pakaian Pilihan');
        $response->assertSee('Pesan via WA');
        $response->assertSee('Tambah ke Keranjang');
        $response->assertSee('Gaun Pesta Maroon');
        $response->assertSee('Kemeja Flanel Oversize');
        $response->assertSee('Blus Sutra Floral');
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
        $response->assertSee('Pesan via WhatsApp Sekarang');
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
}
