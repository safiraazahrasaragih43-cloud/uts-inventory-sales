<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$prods = App\Models\Product::with('category')->get();
foreach ($prods as $p) {
    echo "ID: {$p->id} | SKU: {$p->sku} | Cat: " . ($p->category->name ?? 'None') . " | Name: {$p->name}\n";
    echo "  DB image: {$p->image}\n";
    echo "  Accessor: {$p->image_url}\n";
}
