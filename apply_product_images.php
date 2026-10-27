<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

$productImageMap = [
    'BLUSH-DRS-001' => [
        'url' => 'https://images.unsplash.com/photo-1760172551807-018cc883cff8?auto=format&fit=crop&w=800&q=80',
        'local' => 'products/rose-floral-puff-dress.jpg',
    ],
    'BLUSH-DRS-002' => [
        'url' => 'https://images.unsplash.com/photo-1731335391054-bcb9df422982?auto=format&fit=crop&w=800&q=80',
        'local' => 'products/dusty-pink-satin-dress.jpg',
    ],
    'BLUSH-TOP-001' => [
        'url' => 'https://images.unsplash.com/photo-1745381251702-67b07225e516?auto=format&fit=crop&w=800&q=80',
        'local' => 'products/pastel-peach-ribbon-blouse.jpg',
    ],
    'BLUSH-TOP-002' => [
        'url' => 'https://images.unsplash.com/photo-1772915516567-c1065e48d8cd?auto=format&fit=crop&w=800&q=80',
        'local' => 'products/victorian-lace-linen-shirt.jpg',
    ],
    'BLUSH-SKT-001' => [
        'url' => 'https://images.unsplash.com/photo-1583235340050-f73f853f1b0f?auto=format&fit=crop&w=800&q=80',
        'local' => 'products/soft-rose-pleated-skirt.jpg',
    ],
    'BLUSH-SKT-002' => [
        'url' => 'https://images.unsplash.com/photo-1789110853687-71692f0e86be?auto=format&fit=crop&w=800&q=80',
        'local' => 'products/cream-linen-culottes.jpg',
    ],
    'BLUSH-KNT-001' => [
        'url' => 'https://images.unsplash.com/photo-1687275164205-732738f0c7bc?auto=format&fit=crop&w=800&q=80',
        'local' => 'products/soft-lavender-knit-cardigan.jpg',
    ],
    'BLUSH-KNT-002' => [
        'url' => 'https://images.unsplash.com/photo-1687275166296-7766371fd254?auto=format&fit=crop&w=800&q=80',
        'local' => 'products/oversized-pink-cable-knit.jpg',
    ],
    'BLUSH-HJB-001' => [
        'url' => 'https://images.unsplash.com/photo-1626497361649-81cc097e9bfd?auto=format&fit=crop&w=800&q=80',
        'local' => 'products/blush-silk-voile-hijab.jpg',
    ],
    'BLUSH-HJB-002' => [
        'url' => 'https://images.unsplash.com/photo-1619043599439-9b750b7b2623?auto=format&fit=crop&w=800&q=80',
        'local' => 'products/rose-gold-satin-pashmina.jpg',
    ],
    'BLUSH-DRS-003' => [
        'url' => 'https://images.pexels.com/photos/19895956/pexels-photo-19895956.jpeg?auto=compress&cs=tinysrgb&w=800',
        'local' => 'products/gaun-pesta-maroon-silk.jpg',
    ],
    'BLUSH-TOP-003' => [
        'url' => 'https://images.unsplash.com/photo-1630167146816-e1f4ff99c00c?auto=format&fit=crop&w=800&q=80',
        'local' => 'products/kemeja-flanel-oversize-plaid.jpg',
    ],
    'BLUSH-TOP-004' => [
        'url' => 'https://images.unsplash.com/photo-1622738336927-7584b695fc88?auto=format&fit=crop&w=800&q=80',
        'local' => 'products/blus-sutra-floral-peony.jpg',
    ],
];

$storageDir = storage_path('app/public/products');
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0777, true);
}

foreach ($productImageMap as $sku => $data) {
    $product = Product::where('sku', $sku)->first();
    if ($product) {
        // Save local backup image
        $localPath = storage_path('app/public/' . $data['local']);
        echo "Saving local backup for {$sku} -> {$data['local']}...\n";
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $content = @file_get_contents($data['url'], false, $context);
        if ($content) {
            file_put_contents($localPath, $content);
            echo "  [OK] Downloaded " . round(strlen($content)/1024, 1) . " KB\n";
        }

        // Update database image column with the verified URL
        $product->image = $data['url'];
        $product->save();
        echo "Updated {$sku} ({$product->name}) -> {$product->image}\n";
    } else {
        echo "Product not found: {$sku}\n";
    }
}
echo "Database and storage updated successfully!\n";
