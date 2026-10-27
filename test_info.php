<?php
$photos = [
    'photo-1572804013309-59a88b7e92f1' => 'Rose floral puff midi dress',
    'photo-1515372039744-b8f02a3ae446' => 'Dusty Pink Satin Wrap Dress',
    'photo-1564257631407-4deb1f99d992' => 'Pastel Peach Ribbon Blouse',
    'photo-1551803091-e20673f15770' => 'Victorian Lace Collar Linen Shirt',
    'photo-1583496661160-fb5886a0aaaa' => 'Soft Rose Pleated A-Line Skirt',
    'photo-1509631179647-0177331693ae' => 'Cream High-Waist Linen Culottes',
    'photo-1434389677669-e08b4cac3105' => 'Soft Lavender Cropped Knit Cardigan',
    'photo-1576871337632-b9aef4c17ab9' => 'Oversized Blush Pink Cable Knit',
    'photo-1609357605129-26f69add5d6e' => 'Blush Silk Voile Square Hijab',
    'photo-1601924994987-69e26d50dc26' => 'Rose Gold Satin Silk Pashmina',
    'photo-1566174053879-31528523f8ae' => 'Gaun Pesta Maroon Silk Elegance',
    'photo-1589310243389-96a5483213a8' => 'Kemeja Flanel Oversize Soft Plaid',
    'photo-1584273143981-41c073dfe8f8' => 'Blus Sutra Floral Pastel Peony',
    'photo-1490481651871-ab68de25d43d' => 'Default fallback',
];

foreach ($photos as $id => $label) {
    // Search web query or scrape
    $shortId = str_replace('photo-', '', $id);
    // Unsplash photo pages often are like https://unsplash.com/photos/
    // Let's use search_web or duckduckgo or fetch
    echo "$label ($id)\n";
}
