<?php
$images = [
    // 1. Rose Floral Puff Midi Dress
    'cand_drs1_1' => 'https://images.unsplash.com/photo-1760172551807-018cc883cff8?auto=format&fit=crop&w=600&q=80',
    'cand_drs1_2' => 'https://images.unsplash.com/photo-1620563337464-1c7a727533c2?auto=format&fit=crop&w=600&q=80',
    // 2. Dusty Pink Satin Wrap Dress
    'cand_drs2_1' => 'https://images.unsplash.com/photo-1756411173055-e421d25e4c99?auto=format&fit=crop&w=600&q=80',
    'cand_drs2_2' => 'https://images.unsplash.com/photo-1731335391054-bcb9df422982?auto=format&fit=crop&w=600&q=80',
    // 3. Pastel Peach Ribbon Blouse
    'cand_top1_1' => 'https://images.unsplash.com/photo-1745381251702-67b07225e516?auto=format&fit=crop&w=600&q=80',
    // 4. Victorian Lace Collar Linen Shirt
    'cand_top2_1' => 'https://images.unsplash.com/photo-1772915516567-c1065e48d8cd?auto=format&fit=crop&w=600&q=80',
    'cand_top2_2' => 'https://images.unsplash.com/photo-1708013039663-13176399c6fe?auto=format&fit=crop&w=600&q=80',
    // 5. Soft Rose Pleated A-Line Skirt
    'cand_skt1_1' => 'https://images.unsplash.com/photo-1573878591960-37c788c55728?auto=format&fit=crop&w=600&q=80',
    'cand_skt1_2' => 'https://images.unsplash.com/photo-1552874869-5c39ec9288dc?auto=format&fit=crop&w=600&q=80',
    // 6. Cream High-Waist Linen Culottes
    'cand_skt2_1' => 'https://images.unsplash.com/photo-1789110853687-71692f0e86be?auto=format&fit=crop&w=600&q=80',
    'cand_skt2_2' => 'https://images.unsplash.com/photo-1743764252813-386dedce1a8d?auto=format&fit=crop&w=600&q=80',
    // 7. Soft Lavender Cropped Knit Cardigan
    'cand_knt1_1' => 'https://images.unsplash.com/photo-1687275164205-732738f0c7bc?auto=format&fit=crop&w=600&q=80',
    'cand_knt1_2' => 'https://images.unsplash.com/photo-1553820691-51b6aa80cb7d?auto=format&fit=crop&w=600&q=80',
    // 8. Oversized Blush Pink Cable Knit
    'cand_knt2_1' => 'https://images.unsplash.com/photo-1622925492162-98c3760a7080?auto=format&fit=crop&w=600&q=80',
    'cand_knt2_2' => 'https://images.unsplash.com/photo-1687275166296-7766371fd254?auto=format&fit=crop&w=600&q=80',
    // 11. Gaun Pesta Maroon Silk Elegance
    'cand_drs3_1' => 'https://images.unsplash.com/photo-1773574488220-569921a63d39?auto=format&fit=crop&w=600&q=80',
    'cand_drs3_2' => 'https://images.unsplash.com/photo-1765870972439-d9258966cdce?auto=format&fit=crop&w=600&q=80',
    // 12. Kemeja Flanel Oversize Soft Plaid
    'cand_top3_1' => 'https://images.unsplash.com/photo-1603771687008-372222245e04?auto=format&fit=crop&w=600&q=80',
    'cand_top3_2' => 'https://images.unsplash.com/photo-1610886886310-be2828d6afd5?auto=format&fit=crop&w=600&q=80',
    // 13. Blus Sutra Floral Pastel Peony
    'cand_top4_1' => 'https://images.unsplash.com/photo-1759992878512-ec8f958b13e7?auto=format&fit=crop&w=600&q=80',
    'cand_top4_2' => 'https://images.unsplash.com/photo-1686317422119-e9080fb6b751?auto=format&fit=crop&w=600&q=80',
];

foreach ($images as $k => $u) {
    echo "Downloading $k...\n";
    $content = @file_get_contents($u);
    if ($content) {
        file_put_contents("temp_inspect/{$k}.jpg", $content);
    } else {
        echo "Failed $k\n";
    }
}
echo "Download batch finished!\n";
