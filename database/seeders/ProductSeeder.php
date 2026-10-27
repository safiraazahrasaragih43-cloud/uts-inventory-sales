<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * All product images are curated Unsplash photography featuring strictly
     * garments and flat lay / hanger displays without any humans or models.
     */
    public function run(): void
    {
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
                'image' => 'https://images.unsplash.com/photo-1572005700111-a96a2cb503c2?auto=format&fit=crop&w=800&q=80',
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
                'image' => 'https://images.unsplash.com/photo-1640923160720-35dddb6348ab?auto=format&fit=crop&w=800&q=80',
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
                'image' => 'https://images.unsplash.com/photo-1745381251702-67b07225e516?auto=format&fit=crop&w=800&q=80',
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
                'image' => 'https://images.unsplash.com/photo-1777462985111-9da64fb2e6e6?auto=format&fit=crop&w=800&q=80',
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
                'image' => 'https://images.unsplash.com/photo-1766238955752-0200601334ed?auto=format&fit=crop&w=800&q=80',
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
                'image' => 'https://images.unsplash.com/photo-1687226426353-498d40ec6fbf?auto=format&fit=crop&w=800&q=80',
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
                'image' => 'https://images.unsplash.com/photo-1683315565563-f72590773805?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'category_slug' => 'knitwear-cardigans',
                'name' => 'Oversized Blush Pink Cable Knit',
                'sku' => 'BLUSH-KNT-002',
                'description' => 'Sweater rajut motif kepang kabel lembut tebal hangat dan nyaman dipakai sehari-hari.',
                'purchase_price' => 160000,
                'selling_price' => 279000,
                'stock' => 3,
                'minimum_stock' => 5,
                'image' => 'https://images.unsplash.com/photo-1641642231157-0849081598a2?auto=format&fit=crop&w=800&q=80',
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
                'image' => 'https://images.unsplash.com/photo-1592867874873-85480a35d2aa?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'category_slug' => 'premium-scarves-hijabs',
                'name' => 'Rose Gold Satin Silk Pashmina',
                'sku' => 'BLUSH-HJB-002',
                'description' => 'Pashmina satin sutra tekstur kilau mewah jatuh flowy warna rose champagne.',
                'purchase_price' => 55000,
                'selling_price' => 105000,
                'stock' => 2,
                'minimum_stock' => 6,
                'image' => 'https://images.unsplash.com/photo-1619043599439-9b750b7b2623?auto=format&fit=crop&w=800&q=80',
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
                'image' => 'https://images.unsplash.com/photo-1783159143477-7c027bf65772?auto=format&fit=crop&w=800&q=80',
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
                'image' => 'https://images.unsplash.com/photo-1630167146816-e1f4ff99c00c?auto=format&fit=crop&w=800&q=80',
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
                'image' => 'https://images.unsplash.com/photo-1622738336927-7584b695fc88?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($productsData as $p) {
            $cat = Category::where('slug', $p['category_slug'])->first();
            if ($cat) {
                Product::updateOrCreate(
                    ['sku' => $p['sku']],
                    [
                        'category_id' => $cat->id,
                        'name' => $p['name'],
                        'description' => $p['description'],
                        'purchase_price' => $p['purchase_price'],
                        'selling_price' => $p['selling_price'],
                        'stock' => $p['stock'],
                        'minimum_stock' => $p['minimum_stock'],
                        'image' => $p['image'],
                    ]
                );
            }
        }
    }
}
