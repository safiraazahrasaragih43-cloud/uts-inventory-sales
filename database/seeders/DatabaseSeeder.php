<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users (Admin, Staff, Customer)
        $admin = User::firstOrCreate(
            ['email' => 'admin@blushboutique.com'],
            [
                'name' => 'Owner / Admin Blush',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        $staff = User::firstOrCreate(
            ['email' => 'staff@blushboutique.com'],
            [
                'name' => 'Staff Kasir Blush',
                'password' => Hash::make('password123'),
                'role' => 'staff',
            ]
        );

        $customerUser = User::firstOrCreate(
            ['email' => 'customer@blushboutique.com'],
            [
                'name' => 'Clarissa Stephanie (Member)',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ]
        );

        // 2. Seed Categories
        $categoriesData = [
            [
                'name' => 'Floral & Midi Dresses',
                'slug' => 'floral-midi-dresses',
                'description' => 'Koleksi dress feminin, midi dress floral, dan siluet flowy dengan sentuhan pastel.'
            ],
            [
                'name' => 'Pastel Blouses & Tops',
                'slug' => 'pastel-blouses-tops',
                'description' => 'Blouse anggun berkancing, kerah renda, pita manis untuk tampilan casual & semi-formal.'
            ],
            [
                'name' => 'Pleated Skirts & Culottes',
                'slug' => 'pleated-skirts-culottes',
                'description' => 'Rok plisket premium dan celana kulot bahan jatuh bernuansa nude & cream.'
            ],
            [
                'name' => 'Knitwear & Cardigans',
                'slug' => 'knitwear-cardigans',
                'description' => 'Cardigan rajut halus, knit sweater aesthetic yang nyaman dan chic.'
            ],
            [
                'name' => 'Premium Scarves & Hijabs',
                'slug' => 'premium-scarves-hijabs',
                'description' => 'Jilbab segi empat voal premium dan pashmina silk dengan kilau lembut.'
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['slug']] = Category::firstOrCreate(['slug' => $c['slug']], $c);
        }

        // 3. Seed Suppliers
        $supplier1 = Supplier::firstOrCreate(
            ['name' => 'PT Rose Petal Garment Bandung'],
            [
                'contact_person' => 'Ibu Ratna Dewi',
                'phone' => '081298765432',
                'email' => 'ratna@rosetekstil.co.id',
                'address' => 'Kawasan Industri Tekstil No. 45, Bandung, Jawa Barat'
            ]
        );

        $supplier2 = Supplier::firstOrCreate(
            ['name' => 'CV Blush Textile Jakarta'],
            [
                'contact_person' => 'Bpk. Hendra Wijaya',
                'phone' => '081387654321',
                'email' => 'supply@blushgarment.com',
                'address' => 'Pusat Grosir Tanah Abang Blok A Lt. 3, Jakarta Pusat'
            ]
        );

        $supplier3 = Supplier::firstOrCreate(
            ['name' => 'Silk & Pastel Atelier Solo'],
            [
                'contact_person' => 'Mba Nabila Putri',
                'phone' => '082134567890',
                'email' => 'nabila@silkpastel.com',
                'address' => 'Jl. Slamet Riyadi No. 120, Surakarta, Jawa Tengah'
            ]
        );

        // 4. Seed Products
        $productsData = [
            [
                'category_slug' => 'floral-midi-dresses',
                'name' => 'Rose Floral Puff Midi Dress',
                'sku' => 'BLUSH-DRS-001',
                'description' => 'Midi dress motif mawar vintage dengan lengan puff feminin dan aksen tali pinggang fleksibel.',
                'purchase_price' => 165000,
                'selling_price' => 285000,
                'stock' => 24,
                'minimum_stock' => 5,
                'image' => 'products/dress-1.jpg',
            ],
            [
                'category_slug' => 'floral-midi-dresses',
                'name' => 'Dusty Pink Satin Wrap Dress',
                'sku' => 'BLUSH-DRS-002',
                'description' => 'Dress satin sutra warna dusty pink elegan untuk pesta, bridesmaid, maupun dinner romantis.',
                'purchase_price' => 195000,
                'selling_price' => 349000,
                'stock' => 14,
                'minimum_stock' => 4,
                'image' => 'products/dress-2.jpg',
            ],
            [
                'category_slug' => 'pastel-blouses-tops',
                'name' => 'Pastel Peach Ribbon Blouse',
                'sku' => 'BLUSH-TOP-001',
                'description' => 'Kemeja blouse peach manis dengan kerah pita leher manis, bahan katun airflow adem lembut.',
                'purchase_price' => 95000,
                'selling_price' => 175000,
                'stock' => 30,
                'minimum_stock' => 6,
            ],
            [
                'category_slug' => 'pastel-blouses-tops',
                'name' => 'Victorian Lace Collar Linen Shirt',
                'sku' => 'BLUSH-TOP-002',
                'description' => 'Atasan linen berkerah bordir lace Victorian khas Parisian aesthetic bernuansa soft blush.',
                'purchase_price' => 110000,
                'selling_price' => 199000,
                'stock' => 18,
                'minimum_stock' => 5,
            ],
            [
                'category_slug' => 'pleated-skirts-culottes',
                'name' => 'Soft Rose Pleated A-Line Skirt',
                'sku' => 'BLUSH-SKT-001',
                'description' => 'Rok plisket lipit kecil premium dengan karet elastis yang nyaman dan tidak menerawang.',
                'purchase_price' => 125000,
                'selling_price' => 219000,
                'stock' => 22,
                'minimum_stock' => 5,
            ],
            [
                'category_slug' => 'pleated-skirts-culottes',
                'name' => 'Cream High-Waist Linen Culottes',
                'sku' => 'BLUSH-SKT-002',
                'description' => 'Celana kulot linen high waist dengan potongan lebar chic warna oat milk cream.',
                'purchase_price' => 115000,
                'selling_price' => 195000,
                'stock' => 12,
                'minimum_stock' => 5,
            ],
            [
                'category_slug' => 'knitwear-cardigans',
                'name' => 'Soft Lavender Cropped Knit Cardigan',
                'sku' => 'BLUSH-KNT-001',
                'description' => 'Cardigan rajut warna lavender pastel dengan kancing mutiara vintage yang manis.',
                'purchase_price' => 140000,
                'selling_price' => 245000,
                'stock' => 8,
                'minimum_stock' => 4,
            ],
            [
                'category_slug' => 'knitwear-cardigans',
                'name' => 'Oversized Blush Pink Cable Knit',
                'sku' => 'BLUSH-KNT-002',
                'description' => 'Sweater rajut motif kepang kabel lembut tebal hangat dan nyaman dipakai sehari-hari.',
                'purchase_price' => 160000,
                'selling_price' => 279000,
                'stock' => 3, // Low stock on purpose
                'minimum_stock' => 5,
            ],
            [
                'category_slug' => 'premium-scarves-hijabs',
                'name' => 'Blush Silk Voile Square Hijab',
                'sku' => 'BLUSH-HJB-001',
                'description' => 'Hijab segi empat voal ultrafine mudah dibentuk, adem, dan tegak di dahi dengan laser cut.',
                'purchase_price' => 45000,
                'selling_price' => 89000,
                'stock' => 45,
                'minimum_stock' => 10,
            ],
            [
                'category_slug' => 'premium-scarves-hijabs',
                'name' => 'Rose Gold Satin Silk Pashmina',
                'sku' => 'BLUSH-HJB-002',
                'description' => 'Pashmina satin sutra tekstur kilau mewah jatuh flowy warna rose champagne.',
                'purchase_price' => 55000,
                'selling_price' => 105000,
                'stock' => 2, // Low stock on purpose
                'minimum_stock' => 6,
                'image' => 'https://images.unsplash.com/photo-1601924994987-69e26d50dc26?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'category_slug' => 'floral-midi-dresses',
                'name' => 'Gaun Pesta Maroon Silk Elegance',
                'sku' => 'BLUSH-DRS-003',
                'description' => 'Gaun pesta malam bahan sutra satin warna deep maroon dengan draping pinggang anggun dan siluet ramping mempesona.',
                'purchase_price' => 210000,
                'selling_price' => 385000,
                'stock' => 15,
                'minimum_stock' => 4,
                'image' => 'https://images.unsplash.com/photo-1566174053879-31528523f8ae?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'category_slug' => 'pastel-blouses-tops',
                'name' => 'Kemeja Flanel Oversize Soft Plaid',
                'sku' => 'BLUSH-TOP-003',
                'description' => 'Kemeja flanel berpotongan oversize dengan motif kotak-kotak lembut khas Korean aesthetic, nyaman dipakai santai maupun outer.',
                'purchase_price' => 105000,
                'selling_price' => 189000,
                'stock' => 20,
                'minimum_stock' => 5,
                'image' => 'https://images.unsplash.com/photo-1589310243389-96a5483213a8?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'category_slug' => 'pastel-blouses-tops',
                'name' => 'Blus Sutra Floral Pastel Peony',
                'sku' => 'BLUSH-TOP-004',
                'description' => 'Blus sutra premium motif bunga peony lembut dengan aksen kerah pita feminin dan tekstur kain sehalus sutra.',
                'purchase_price' => 125000,
                'selling_price' => 225000,
                'stock' => 16,
                'minimum_stock' => 5,
                'image' => 'https://images.unsplash.com/photo-1584273143981-41c073dfe8f8?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $p) {
            $cat = $categories[$p['category_slug']];
            $prod = Product::updateOrCreate(
                ['sku' => $p['sku']],
                [
                    'category_id' => $cat->id,
                    'name' => $p['name'],
                    'description' => $p['description'],
                    'purchase_price' => $p['purchase_price'],
                    'selling_price' => $p['selling_price'],
                    'stock' => $p['stock'],
                    'minimum_stock' => $p['minimum_stock'],
                    'image' => $p['image'] ?? null,
                ]
            );
            $createdProducts[] = $prod;
        }

        // 5. Seed 1 Sample Purchase Transaction
        $purchase = Purchase::firstOrCreate(
            ['invoice_number' => 'PUR-20261020-0001'],
            [
                'supplier_id' => $supplier1->id,
                'user_id' => $admin->id,
                'purchase_date' => now()->subDays(4)->toDateString(),
                'total_amount' => 4700000,
                'notes' => 'Restock awal koleksi Blush Boutique musim ini',
            ]
        );

        if ($purchase->wasRecentlyCreated) {
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $createdProducts[0]->id,
                'quantity' => 20,
                'unit_price' => $createdProducts[0]->purchase_price,
                'subtotal' => 20 * $createdProducts[0]->purchase_price,
            ]);
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $createdProducts[2]->id,
                'quantity' => 15,
                'unit_price' => $createdProducts[2]->purchase_price,
                'subtotal' => 15 * $createdProducts[2]->purchase_price,
            ]);
        }

        // 6. Seed Customers
        $this->call(CustomerSeeder::class);
        $sampleCustomer = Customer::first();

        // 7. Seed 1 Sample Sale Transaction
        $sale = Sale::firstOrCreate(
            ['invoice_number' => 'SLS-20261024-0001'],
            [
                'user_id' => $staff->id,
                'customer_id' => $sampleCustomer ? $sampleCustomer->id : null,
                'customer_name' => $sampleCustomer ? $sampleCustomer->name : 'Clarissa Stephanie',
                'sale_date' => now()->subHours(2),
                'total_amount' => 460000,
                'payment_method' => 'QRIS / Transfer Bank',
                'notes' => 'Pembelian langsung di boutique',
            ]
        );

        if ($sale->wasRecentlyCreated) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $createdProducts[0]->id,
                'quantity' => 1,
                'unit_price' => $createdProducts[0]->selling_price,
                'subtotal' => $createdProducts[0]->selling_price,
            ]);
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $createdProducts[2]->id,
                'quantity' => 1,
                'unit_price' => $createdProducts[2]->selling_price,
                'subtotal' => $createdProducts[2]->selling_price,
            ]);
        }
    }
}
