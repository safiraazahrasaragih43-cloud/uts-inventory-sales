<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== Memperbarui & Mengunduh Gambar Produk Hijab BlushBoutique ===\n\n";

$hijabProducts = [
    'BLUSH-HJB-001' => [
        'name' => 'Blush Silk Voile Square Hijab',
        'url' => 'https://images.unsplash.com/photo-1626497361649-81cc097e9bfd?auto=format&fit=crop&w=800&q=80',
        'local_file' => 'storage/app/public/products/blush-silk-voile-hijab.jpg',
        'description' => 'Model mengenakan hijab segi empat voal warna dusty blush pink, tegak di dahi, kain adem lembut dengan latar studio putih bersih.'
    ],
    'BLUSH-HJB-002' => [
        'name' => 'Rose Gold Satin Silk Pashmina',
        'url' => 'https://images.unsplash.com/photo-1619043599439-9b750b7b2623?auto=format&fit=crop&w=800&q=80',
        'local_file' => 'storage/app/public/products/rose-gold-satin-pashmina.jpg',
        'description' => 'Pashmina satin sutra tekstur kilau mewah jatuh flowy bernuansa warna rose gold / rose champagne asli.'
    ],
];

// Pastikan direktori tujuan tersedia
$storageDir = __DIR__ . '/storage/app/public/products';
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0755, true);
}

foreach ($hijabProducts as $sku => $info) {
    echo "Memproses SKU {$sku} ({$info['name']})...\n";
    echo "  Sumber: {$info['url']}\n";
    echo "  Karakteristik: {$info['description']}\n";
    
    // Unduh gambar ke storage lokal
    $targetPath = __DIR__ . '/' . $info['local_file'];
    $imageContent = @file_get_contents($info['url']);
    if ($imageContent !== false && strlen($imageContent) > 1000) {
        file_put_contents($targetPath, $imageContent);
        echo "  [OK] Gambar berhasil diunduh ke {$info['local_file']} (" . round(strlen($imageContent) / 1024, 1) . " KB)\n";
    } else {
        echo "  [PERINGATAN] Gagal mengunduh gambar lokal, tetap memperbarui URL database.\n";
    }
    
    // Perbarui data produk di database
    $product = Product::where('sku', $sku)->first();
    if ($product) {
        $product->image = $info['url'];
        $product->save();
        echo "  [OK] Database produk ID {$product->id} berhasil diperbarui dengan URL gambar baru.\n";
    } else {
        echo "  [PERINGATAN] Produk dengan SKU {$sku} tidak ditemukan di database.\n";
    }
    echo "\n";
}

echo "=== Selesai! Seluruh gambar produk hijab telah diperbarui secara lengkap. ===\n";
